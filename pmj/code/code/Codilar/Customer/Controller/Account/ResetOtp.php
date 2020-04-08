<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\Customer\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Codilar\Customer\Helper\Data as CustomerHelper;
/**
 * Class ResetOtp
 * @package Codilar\Customer\Controller\Account
 */
class ResetOtp extends \Magento\Framework\App\Action\Action
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $_registry;
    /**
     * @var CustomerHelper
     */
    private $_customerHelper;

    /**
     * ResetOtp constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $registry
     * @param CustomerHelper $customerHelper
     * @param PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry,
        CustomerHelper $customerHelper,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_registry = $registry;
        $this->_customerHelper = $customerHelper;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        if(!$this->_customerHelper->isCustomerLoggedIn()){
            $mobileNumber = (int)$this->getRequest()->getParam('mobilenumber');
            $this->_registry->register('mobile_number', $mobileNumber);
            $customerId = (int)$this->getRequest()->getParam('id');
            $this->_registry->register('customer_id', $customerId);
            return $this->_pageFactory->create();
        }
        else{
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            $resultRedirect->setPath('customer/account');
            return $resultRedirect;
        }
    }
}
