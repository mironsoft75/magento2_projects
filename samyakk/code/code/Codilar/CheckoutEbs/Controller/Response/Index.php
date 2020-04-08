<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 2:53 PM
 */

namespace Codilar\CheckoutEbs\Controller\Response;

use Codilar\CheckoutEbs\Helper\Crypto;
use Codilar\CheckoutEbs\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\CsrfAwareActionInterface;
use Magento\Framework\App\Request\InvalidRequestException;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Codilar\Checkout\Helper\Order as OrderHelper;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Codilar\Checkout\Helper\Payment;
use Codilar\CheckoutEbs\Model\Ebs;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * Class Index
 * @package Codilar\CheckoutEbs\Controller\Resonse
 */
class Index extends Action implements CsrfAwareActionInterface
{
    /**
     * Crypto
     *
     * @var Crypto
     */
    private $_crypto;
    /**
     * Config
     *
     * @var Config
     */
    private $_config;
    /**
     * OrderHelper
     *
     * @var OrderHelper
     */
    private $_orderHelper;
    /**
     * OrderManagementInterface
     *
     * @var OrderManagementInterface
     */
    private $_orderManagement;
    /**
     * OrderRepository
     *
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;
    /**
     * Payment
     *
     * @var Payment
     */
    private $_paymentHelper;
    /**
     * OrderInterface
     *
     * @var OrderInterface
     */
    private $_orderInterface;
    /**
     * SearchCriteria
     *
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * Index constructor.
     * @param Context $context
     * @param Crypto $crypto
     * @param Config $config
     * @param OrderHelper $orderHelper
     * @param OrderManagementInterface $orderManagement
     * @param OrderRepositoryInterface $orderRepository
     * @param Payment $paymentHelper
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param OrderInterface $orderInterface
     */
    public function __construct(
        Context $context,
        Crypto $crypto,
        Config $config,
        OrderHelper $orderHelper,
        OrderManagementInterface $orderManagement,
        OrderRepositoryInterface $orderRepository,
        Payment $paymentHelper,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        OrderInterface $orderInterface
    )
    {
        parent::__construct($context);
        $this->_crypto = $crypto;
        $this->_config = $config;
        $this->_orderHelper = $orderHelper;
        $this->_orderManagement = $orderManagement;
        $this->_orderRepository = $orderRepository;
        $this->_paymentHelper = $paymentHelper;
        $this->_orderInterface = $orderInterface;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;

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
        $secret_key = $this->_config->getSecretKey();
        $resultRedirect = $this->resultRedirectFactory->create();

        if (isset($_REQUEST)) {
            $response = $_REQUEST;
            $sh = $response['SecureHash'];
            $hashValue = $this->_crypto->decryptResponse($response, $secret_key);
            if ($sh == $hashValue) {
                $incrementId = $response['MerchantRefNo'];
                $searchCriteria = $this->searchCriteriaBuilder
                    ->addFilter('increment_id', $incrementId, 'eq')->create();
                $orderList = $this->_orderRepository
                    ->getList($searchCriteria)->getItems();
                foreach ($orderList as $order) {
                    $orderId = $order->getEntityId();
                }


                /**
                 * Order
                 *
                 * @var \Magento\Sales\Model\Order $order
                 */
                $order = $this->_orderRepository->get($orderId);
                $quoteId = $order->getQuoteId();

                $paymentMethod = $this->_paymentHelper
                    ->addCsrfTokenForPaymentMethod(Ebs::CODE, $quoteId);

                // check for payment success
                if ($response['ResponseCode'] == 0) {
                    $this->paymentSuccess($order, $resultRedirect, $paymentMethod);
                }
                // check for payment failed
                if ($response['ResponseCode'] != 0) {
                    $this->paymentFailed($order, $resultRedirect, $paymentMethod);
                }

            } else {
                $resultRedirect->setRefererOrBaseUrl();
            }

        } else {
            $resultRedirect->setRefererOrBaseUrl();
        }
        return $resultRedirect;


    }

    /**
     * PaymentSuccess
     *
     * @param $order
     * @param $resultRedirect
     * @param $paymentMethod
     */
    public function paymentSuccess($order, $resultRedirect, $paymentMethod)
    {
        $this->_orderHelper->setStatusAndState(
            $order,
            $this->_config->getSuccessOrderStatus(),
            "Ebs Order Payment Success"
        );
        $this->_orderManagement->notify($order->getEntityId());
        $resultRedirect->setUrl(
            $this->_config->getResponseReturnUrl()
            . $paymentMethod->getCsrfToken()
            . "?type=" . Ebs::CODE
        );
    }

    /**
     * PaymentFailed
     *
     * @param $order
     * @param $resultRedirect
     * @param $paymentMethod
     */
    public function paymentFailed($order, $resultRedirect, $paymentMethod)
    {
        $this->_orderHelper->setStatusAndState(
            $order,
            $this->_config->getFailureOrderStatus(),
            "Ebs Order Payment Failed"
        );
        $resultRedirect->setUrl(
            $this->_config->getResponseReturnUrl() .
            $paymentMethod->getCsrfToken() .
            "?type=" . Ebs::CODE
        );
    }

    /**
     * Create exception in case CSRF validation failed.
     * Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(
        RequestInterface $request
    ): ?InvalidRequestException
    {
        return null;
    }

    /**
     * Perform custom request validation.
     * Return null if default validation is needed.
     *
     * @param RequestInterface $request
     *
     * @return bool|null
     */
    public function validateForCsrf(RequestInterface $request): ?bool
    {
        return true;
    }
}
