<?php

namespace Magento\Ccavenuepay\Model\Order;

use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Email\Sender\OrderSender;
use Magento\Payment\Helper\Data as PaymentHelper;
use Magento\Sales\Model\Order\Email\Container\OrderIdentity;
use Magento\Sales\Model\Order\Email\Container\Template;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;
use Magento\Sales\Model\Order\Address\Renderer;
use Magento\Framework\Event\ManagerInterface;

class OrderEmail extends OrderSender
{
    protected $logger;
    protected $orderResource;
    protected $globalConfig;

    public function __construct(
        Template $templateContainer,
        OrderIdentity $identityContainer,
        \Magento\Sales\Model\Order\Email\SenderBuilderFactory $senderBuilderFactory,
        \Psr\Log\LoggerInterface $logger,
        Renderer $addressRenderer,
        PaymentHelper $paymentHelper,
        OrderResource $orderResource,
        \Magento\Framework\App\Config\ScopeConfigInterface $globalConfig,
        ManagerInterface $eventManager
    )
    {
        parent::__construct($templateContainer, $identityContainer, $senderBuilderFactory, $logger, $addressRenderer, $paymentHelper, $orderResource, $globalConfig, $eventManager);

        $this->orderResource = $orderResource;
        $this->globalConfig = $globalConfig;

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $this->logger = new \Zend\Log\Logger();
        $this->logger->addWriter($writer);
        $this->logger->info("__construct Observer DataAssignObserver");
    }

    public function send(Order $order, $forceSyncMode = false, $flag = false)
    {
        $payment = $order->getPayment()->getMethodInstance()->getCode();
        $payment_confirmation_mail = $this->globalConfig->getValue('payment/ccavenuepay/payment_confirmation_mail');

        $this->logger->info("Order Code == " . $payment);
        $this->logger->info("Email After Success == " . $payment_confirmation_mail);

        if ($payment == 'ccavenuepay' && !$flag && $payment_confirmation_mail) {
            $this->logger->info("== Order email stop ==");
            return false;
        }
        $order->setSendEmail(true);

        if (!$this->globalConfig->getValue('sales_email/general/async_sending') || $forceSyncMode) {
            if ($this->checkAndSend($order)) {
                $order->setEmailSent(true);
                $this->orderResource->saveAttribute($order, ['send_email', 'email_sent']);
                return true;
            }
        } else {
            $order->setEmailSent(null);
            $this->orderResource->saveAttribute($order, 'email_sent');
        }

        $this->orderResource->saveAttribute($order, 'send_email');
        return false;
    }
}

