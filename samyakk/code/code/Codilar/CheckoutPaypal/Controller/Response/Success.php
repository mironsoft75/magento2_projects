<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Controller\Response;

use Codilar\CheckoutPaypal\Helper\PaypalApi;
use Codilar\CheckoutPaypal\Helper\RequestData;
use Codilar\CheckoutPaypal\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Sales\Api\OrderRepositoryInterface;
use Codilar\Checkout\Helper\Order as OrderHelper;
use Codilar\Checkout\Helper\Payment;
use Codilar\CheckoutPaypal\Model\Payment\CheckoutPaypal;
use Magento\Sales\Api\OrderManagementInterface;
use Codilar\Pwa\Model\Config as PwaConfig;

class Success extends Action
{

    /**
     * @var PaypalApi
     */
    private $paypalApi;
    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var OrderHelper
     */
    private $orderHelper;
    /**
     * @var Payment
     */
    private $paymentHelper;
    /**
     * @var RequestData
     */
    private $requestData;
    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;
    /**
     * @var PwaConfig
     */
    private $pwaConfig;

    /**
     * Response constructor.
     * @param Context $context
     * @param PaypalApi $paypalApi
     * @param Curl $curl
     * @param OrderRepositoryInterface $orderRepository
     * @param Config $config
     * @param OrderHelper $orderHelper
     * @param Payment $paymentHelper
     * @param RequestData $requestData
     * @param OrderManagementInterface $orderManagement
     * @param PwaConfig $pwaConfig
     */
    public function __construct(
        Context $context,
        PaypalApi $paypalApi,
        Curl $curl,
        OrderRepositoryInterface $orderRepository,
        Config $config,
        OrderHelper $orderHelper,
        Payment $paymentHelper,
        RequestData $requestData,
        OrderManagementInterface $orderManagement,
        PwaConfig $pwaConfig
    )
    {
        parent::__construct($context);
        $this->paypalApi = $paypalApi;
        $this->curl = $curl;
        $this->orderRepository = $orderRepository;
        $this->config = $config;
        $this->orderHelper = $orderHelper;
        $this->paymentHelper = $paymentHelper;
        $this->requestData = $requestData;
        $this->orderManagement = $orderManagement;
        $this->pwaConfig = $pwaConfig;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();
        $paymentId = $params['paymentId'];
        $payerId = $params['PayerID'];

        $data = $this->requestData->getPaypalConformationRequestData($payerId);

        $response = $this->paypalApi->checkPaypalConformation($paymentId, json_decode($data));
        if (isset($response->transactions)) {
            $transations = $response->transactions;
            foreach ($transations as $transation) {
                $orderId = $transation->invoice_number;
            }

            /** @var \Magento\Sales\Api\Data\OrderInterface $order */
            $order = $this->orderRepository->get($orderId);
            $quoteId = $order->getQuoteId();
            $paymentMethod = $this->paymentHelper->addCsrfTokenForPaymentMethod(CheckoutPaypal::CODE, $quoteId);
            if ($response->state == 'approved') {
                $this->orderHelper->setStatusAndState($order, $this->config->getSuccessOrderStatus(), "Paypal Order Success of Paypal ID " . $paymentId);
                $this->orderManagement->notify($orderId);
                $resultRedirect->setUrl($this->config->getResponseReturnUrl() . $paymentMethod->getCsrfToken() . "?type=" . CheckoutPaypal::CODE);
            } else {
                $this->orderHelper->setStatusAndState($order, $this->config->getFailureOrderStatus(), "Paypal Order Failed of Paypal ID " . $paymentId);
                $resultRedirect->setUrl($this->config->getResponseReturnUrl() . $paymentMethod->getCsrfToken() . "?type=" . CheckoutPaypal::CODE);
            }
        } else {
            echo "OOPS... There's been an error during Transaction";
            sleep(5);
            $resultRedirect->setUrl($this->pwaConfig->getRedirectBaseUrl());
        }

        return $resultRedirect;
    }
}