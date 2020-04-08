<?php

namespace Codilar\Customer\Observers\Frontend;

use Magento\Framework\App\Response\Http;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\UrlInterface;
use Codilar\Customer\Helper\Data as CustomerHelper;

class Checkout implements ObserverInterface {
    /**
     * @var Http
     */
    protected $_response;
    /**
     * @var UrlInterface
     */
    protected $_url;
    /**
     * @var \Codilar\Customer\Helper\Login
     */
    protected $loginHelper;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var CustomerHelper
     */
    private $_customerHelper;

    /**
     * Checkout constructor.
     * @param Http $response
     * @param UrlInterface $url
     * @param \Codilar\Customer\Helper\Login $loginHelper
     * @param CustomerHelper $customerHelper
     * @param ManagerInterface $messageManager
     */
    public function __construct(
        Http $response,
        UrlInterface $url,
        \Codilar\Customer\Helper\Login $loginHelper,
        CustomerHelper $customerHelper,
        ManagerInterface $messageManager
    )
    {
        $this->_response = $response;
        $this->_url = $url;
        $this->loginHelper = $loginHelper;
        $this->messageManager = $messageManager;
        $this->_customerHelper = $customerHelper;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->isLoggedIn()) {
            if(!$this->isNumberVerified()) {
                $this->setAfterLoginUrl();
                $this->messageManager->addWarningMessage(__("Verify mobile number to continue"));
                $this->_response->setRedirect($this->_url->getUrl('customer/account/edit'))->sendResponse();
                exit(0);
            }
        }
        return $this;
    }

    /**
     *
     */
    protected function setAfterLoginUrl(){
        $afterLoginUrl = $this->_url->getUrl('checkout');
        $this->loginHelper->setAfterLoginUrl($afterLoginUrl);
    }

    /**
     * @return bool
     */
    protected function isLoggedIn(){
        return $this->_customerHelper->isCustomerLoggedIn();
    }

    /**
     * @return bool
     */
    protected function isNumberVerified(){
        return $this->_customerHelper->isCustomerOtpVerifiedFromSession();
    }



}