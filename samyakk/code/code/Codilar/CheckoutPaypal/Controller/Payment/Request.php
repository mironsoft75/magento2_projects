<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Controller\Payment;

use Codilar\Api\Helper\Customer;
use Codilar\Checkout\Helper\Order as OrderHelper;
use Codilar\Checkout\Helper\Payment;
use Codilar\CheckoutPaypal\Api\CheckoutPaypalRepositoryInterface;
use Codilar\CheckoutPaypal\Helper\PaypalApi;
use Codilar\CheckoutPaypal\Helper\RequestData;
use Codilar\CheckoutPaypal\Logger\Logger;
use Codilar\CheckoutPaypal\Model\Config;
use Codilar\CheckoutPaypal\Model\Payment\CheckoutPaypal;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

class Request extends Action
{
    /**
     * @var PaypalApi
     */
    private $paypalApi;
    /**
     * @var Http
     */
    private $httpRequest;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var TokenFactory
     */
    private $tokenFactory;
    /**
     * @var RequestData
     */
    private $requestData;
    /**
     * @var CheckoutPaypalRepositoryInterface
     */
    private $checkoutPaypalRepository;
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var Order
     */
    private $order;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var OrderHelper
     */
    private $orderHelper;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Payment
     */
    private $paymentHelper;

    /**
     * CheckoutPaypal constructor.
     * @param Context $context
     * @param PaypalApi $paypalApi
     * @param Http $httpRequest
     * @param Session $session
     * @param TokenFactory $tokenFactory
     * @param RequestData $requestData
     * @param CheckoutPaypalRepositoryInterface $checkoutPaypalRepository
     * @param Customer $customerHelper
     * @param Order $order
     * @param OrderRepositoryInterface $orderRepository
     * @param Logger $logger
     * @param OrderHelper $orderHelper
     * @param Config $config
     * @param Payment $paymentHelper
     */
    public function __construct(
        Context $context,
        PaypalApi $paypalApi,
        Http $httpRequest,
        Session $session,
        TokenFactory $tokenFactory,
        RequestData $requestData,
        CheckoutPaypalRepositoryInterface $checkoutPaypalRepository,
        Customer $customerHelper,
        Order $order,
        OrderRepositoryInterface $orderRepository,
        Logger $logger,
        OrderHelper $orderHelper,
        Config $config,
        Payment $paymentHelper
    ) {
        parent::__construct($context);
        $this->paypalApi = $paypalApi;
        $this->httpRequest = $httpRequest;
        $this->session = $session;
        $this->tokenFactory = $tokenFactory;
        $this->requestData = $requestData;
        $this->checkoutPaypalRepository = $checkoutPaypalRepository;
        $this->customerHelper = $customerHelper;
        $this->order = $order;
        $this->orderRepository = $orderRepository;
        $this->logger = $logger;
        $this->orderHelper = $orderHelper;
        $this->config = $config;
        $this->paymentHelper = $paymentHelper;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\AlreadyExistsException
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $customerToken = $this->getRequest()->getParam('token');
        $resultRedirect = $this->resultRedirectFactory->create();
        $customerId = $this->customerHelper->getCustomerIdByToken(false, $customerToken);
        $order = $this->orderRepository->get($orderId);
        $this->orderHelper->setStatusAndState($order, $this->config->getPendingOrderStatus(), "Paypal Order Pending");
        if ($customerId == $order->getCustomerId()) {
            try {
                $this->checkoutPaypalRepository->load($orderId, 'order_id');
                $resultRedirect->setRefererOrBaseUrl();
            } catch (NoSuchEntityException $e) {
                $orderData = $this->requestData->getRequestData($orderId);
                $accessTokenResponse = $this->paypalApi->getPaypalAccessToken();
                try {
                    $accessToken = $accessTokenResponse->access_token;
                    $response = $this->paypalApi->getPaypalOrderResponse($accessToken, json_decode($orderData));
                    $responseArray = json_decode(json_encode($response), true);
                    if (isset($responseArray['debug_id'])) {
                        $url = $this->cancelOrder($order, $this->config->getFailureOrderStatus(), $responseArray['message']);
                        $resultRedirect->setUrl($url);
                    } else {
                        try {
                            $links = $response->links;
                            foreach ($links as $link) {
                                if ($link->method == "REDIRECT") {
                                    $redirectUrl = $link->href;
                                    $token = $this->requestData->getToken($redirectUrl);
                                }
                            }
                            $checkoutPaypal = $this->checkoutPaypalRepository->create();
                            $checkoutPaypal->setOrderId($orderId)
                                ->setPaypalPayId($response->id)
                                ->setPaypalToken($token);
                            $this->checkoutPaypalRepository->save($checkoutPaypal);

                            $resultRedirect->setUrl($redirectUrl);
                        } catch (\Exception $exception) {
                            foreach ($response->details as $detail) {
                                $this->logger->err($detail->issue);
                            }
                            $url = $this->cancelOrder($order, $this->config->getFailureOrderStatus(), "Paypal Order failed due to Invalid Order Data");
                            $resultRedirect->setUrl($url);
                        }
                    }
                } catch (\Exception $exception) {
                    $this->logger->err($accessTokenResponse->error_description);
                    $url = $this->cancelOrder($order, $this->config->getFailureOrderStatus(), "Paypal Order failed by invalid token ID");
                    $resultRedirect->setUrl($url);
                }
            }
        } else {
            $resultRedirect->setRefererOrBaseUrl();
        }

        return $resultRedirect;
    }

    /**
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Api\Data\OrderInterface $order
     * @param string $status
     * @param string $message
     * @return string
     * @throws NoSuchEntityException
     */
    protected function cancelOrder($order, $status, $message)
    {
        $this->orderHelper->setStatusAndState($order, $status, $message);
        $quoteId = $order->getQuoteId();
        $paymentMethod = $this->paymentHelper->addCsrfTokenForPaymentMethod(CheckoutPaypal::CODE, $quoteId);
        return $this->config->getResponseReturnUrl() . $paymentMethod->getCsrfToken() . "?type=" . CheckoutPaypal::CODE;
    }
}
