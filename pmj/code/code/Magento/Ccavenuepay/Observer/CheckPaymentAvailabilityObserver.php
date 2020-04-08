<?php

namespace Magento\Ccavenuepay\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Ccavenuepay\Helper\Data as CcavenueHelper;

class CheckPaymentAvailabilityObserver implements ObserverInterface {

    protected $_ccavenueHelper;
    protected $_checkoutSession;
    protected $logger;

    public function __construct( CcavenueHelper $CcavenueHelper, CheckoutSession $checkoutSession )
    {
        $this->_ccavenueHelper = $CcavenueHelper;
        $this->_checkoutSession = $checkoutSession;

        $writer = new \Zend\Log\Writer\Stream(BP . '/var/log/test.log');
        $this->logger = new \Zend\Log\Logger();
        $this->logger->addWriter($writer);
        $this->logger->info("__construct Observer Payment Availablility");
    }

    public function execute(Observer $observer)
    {
        $methodInstance = $observer->getEvent()->getMethodInstance();
        $this->logger->info("Availablility code == ".$methodInstance->getCode());
        if ($methodInstance->getCode() != 'ccavenuepay') {
            return $this;
        }

        $result = $observer->getEvent()->getResult();
        $result->setData('is_available', true);

        $quote = $this->_checkoutSession->getQuote();
        $orderCountryCode = $quote->getShippingAddress()->getCountry(); //current order country code
        $orderCurrencyCode = $quote->getQuoteCurrencyCode();            //current order currency code

        /**** Checking allowed country ****/
        if ($this->_ccavenueHelper->getAllowedCountry($orderCountryCode) === false) {
            $result->setData('is_available', false);
        }
        
        /**** Checking allowed currency ****/
        if ($this->_ccavenueHelper->getAllowedCurrency($orderCurrencyCode) === false) {
            $result->setData('is_available', false);
        }

    }
}

