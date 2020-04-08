<?php

namespace Codilar\Customer\Observers\Frontend;

use Magento\Framework\App\ResponseFactory;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use \Codilar\Customer\Helper\Login as LoginHelper;
use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Framework\Message\ManagerInterface;
/**
 * Class Login
 * @package Codilar\Customer\Observers\Frontend
 */
class Login implements ObserverInterface
{
    /**
     * @var UrlInterface
     */
    protected $_url;
    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;
    /**
     * @var Registry
     */
    protected $_registry;
    /**
     * @var LoginHelper
     */
    protected $loginHelper;
    /**
     * @var CustomerHelper
     */
    private $_customerHelper;
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * Login constructor.
     * @param UrlInterface $url
     * @param ResponseFactory $responseFactory
     * @param Registry $registry
     * @param LoginHelper $loginHelper
     * @param CustomerHelper $customerHelper
     * @param ManagerInterface $messageManager
     */
    function __construct(
        UrlInterface $url,
        ResponseFactory $responseFactory,
        Registry $registry,
        LoginHelper $loginHelper,
        CustomerHelper $customerHelper,
        ManagerInterface $messageManager
    )
    {
        $this->_url = $url;
        $this->_response = $responseFactory->create();
        $this->_registry = $registry;
        $this->loginHelper = $loginHelper;
        $this->_customerHelper = $customerHelper;
        $this->messageManager = $messageManager;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return bool|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if($this->_customerHelper->isCustomerLoggedIn()){
            $otpStatus = $this->_customerHelper->isCustomerOtpVerifiedFromSession();
            if($otpStatus != 1 && !$this->_registry->registry('is_social_login')){
//                $this->messageManager->addWarningMessage(__("Verify mobile number to continue."));
//                $this->_response->setRedirect($this->_url->getUrl('customer/account/edit'))->sendResponse();
//                exit(0);
            }
        }
    }

}