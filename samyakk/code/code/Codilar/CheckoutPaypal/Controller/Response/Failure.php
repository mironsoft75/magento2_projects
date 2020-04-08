<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Controller\Response;


use Codilar\CheckoutPaypal\Api\CheckoutPaypalRepositoryInterface;
use Codilar\CheckoutPaypal\Model\Config;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Codilar\Checkout\Helper\Order as OrderHelper;
use Codilar\Checkout\Helper\Payment;
use Codilar\CheckoutPaypal\Model\Payment\CheckoutPaypal;

class Failure extends Action
{
    /**
     * @var CheckoutPaypalRepositoryInterface
     */
    private $checkoutPaypalRepository;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
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
     * Failure constructor.
     * @param Context $context
     * @param CheckoutPaypalRepositoryInterface $checkoutPaypalRepository
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderHelper $orderHelper
     * @param Config $config
     * @param Payment $paymentHelper
     */
    public function __construct(
        Context $context,
        CheckoutPaypalRepositoryInterface $checkoutPaypalRepository,
        OrderRepositoryInterface $orderRepository,
        OrderHelper $orderHelper,
        Config $config,
        Payment $paymentHelper
    )
    {
        parent::__construct($context);
        $this->checkoutPaypalRepository = $checkoutPaypalRepository;
        $this->orderRepository = $orderRepository;
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
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $params = $this->getRequest()->getParams();
        $token = $params['token'];

        try {
            $data = $this->checkoutPaypalRepository->load($token, 'paypal_token');
            $orderId = $data->getOrderId();
            /** @var \Magento\Sales\Api\Data\OrderInterface $order */
            $order = $this->orderRepository->get($orderId);
            $this->orderHelper->setStatusAndState($order,$this->config->getFailureOrderStatus(),"Paypal Order failed of Paypal ID ".$data->getPaypalPayId());
            $quoteId = $order->getQuoteId();
            $paymentMethod = $this->paymentHelper->addCsrfTokenForPaymentMethod(CheckoutPaypal::CODE, $quoteId);
            $resultRedirect->setUrl($this->config->getResponseReturnUrl().$paymentMethod->getCsrfToken()."?type=".CheckoutPaypal::CODE);
        } catch (NoSuchEntityException $e) {
        }

        return $resultRedirect;
    }
}