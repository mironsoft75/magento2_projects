<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Controller\Response;


use Codilar\CheckoutCcAvenue\Helper\Crypto;
use Codilar\CheckoutCcAvenue\Model\Config;
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
use Codilar\CheckoutCcAvenue\Model\CcAvenue;
use Magento\Sales\Api\Data\OrderInterface;

class Index extends Action implements CsrfAwareActionInterface
{
    /**
     * @var Crypto
     */
    private $crypto;
    /**
     * @var Config
     */
    private $config;
    /**
     * @var OrderHelper
     */
    private $orderHelper;
    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var Payment
     */
    private $paymentHelper;
    /**
     * @var OrderInterface
     */
    private $orderInterface;

    /**
     * Index constructor.
     * @param Context $context
     * @param Crypto $crypto
     * @param Config $config
     * @param OrderHelper $orderHelper
     * @param OrderManagementInterface $orderManagement
     * @param OrderRepositoryInterface $orderRepository
     * @param Payment $paymentHelper
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
        OrderInterface $orderInterface
    )
    {
        parent::__construct($context);
        $this->crypto = $crypto;
        $this->config = $config;
        $this->orderHelper = $orderHelper;
        $this->orderManagement = $orderManagement;
        $this->orderRepository = $orderRepository;
        $this->paymentHelper = $paymentHelper;
        $this->orderInterface = $orderInterface;
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
        $responseData = $this->getRequest()->getParam('encResp');
        $rcvdString = $this->crypto->decrypt($responseData, $this->config->getSecretKey());
        $orderStatus = "";
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);
        if (isset($responseData)) {
            for ($i = 0; $i < $dataSize; $i++) {
                $information = explode('=', $decryptValues[$i]);
                if ($i == 0) {
                    $incrementId = $information[1];
                }
                if ($i == 3) {
                    $orderStatus = $information[1];
                }
            }

            /** @var \Magento\Sales\Model\Order $order */
            $order = $this->orderInterface->loadByIncrementId($incrementId);
            $quoteId = $order->getQuoteId();
            $paymentMethod = $this->paymentHelper->addCsrfTokenForPaymentMethod(CcAvenue::CODE, $quoteId);
            if ($orderStatus === "Success") {
                $this->orderHelper->setStatusAndState($order, $this->config->getSuccessOrderStatus(), "CcAvenue Order Payment Success");
                $this->orderManagement->notify($order->getEntityId());
                $resultRedirect->setUrl($this->config->getResponseReturnUrl() . $paymentMethod->getCsrfToken() . "?type=" . CcAvenue::CODE);
            } else {
                $this->orderHelper->setStatusAndState($order, $this->config->getFailureOrderStatus(), "CcAvenue Order Payment Failed");
                $resultRedirect->setUrl($this->config->getResponseReturnUrl() . $paymentMethod->getCsrfToken() . "?type=" . CcAvenue::CODE);
            }

        } else {
            $resultRedirect->setRefererOrBaseUrl();
        }

        return $resultRedirect;
    }

    /**
     * Create exception in case CSRF validation failed.
     * Return null if default exception will suffice.
     *
     * @param RequestInterface $request
     *
     * @return InvalidRequestException|null
     */
    public function createCsrfValidationException(RequestInterface $request): ?InvalidRequestException
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