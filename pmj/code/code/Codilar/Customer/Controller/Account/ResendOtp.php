<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\Customer\Controller\Account;

use Codilar\Customer\Helper\Data as CustomerHelper;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\CustomerFactory;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;

/**
 * Class ResendOtp
 * @package Codilar\Customer\Controller\Account
 */
class ResendOtp extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Validator
     */
    protected $_formKeyValidator;
    /**
     * @var CustomerHelper
     */
    protected $_customerHelper;
    /**
     * @var UrlInterface
     */
    protected $url;
    /**
     * @var CustomerFactory
     */
    private $customerFactory;
    /**
     * @var AccountManagement
     */
    private $accountManagement;
    /**
     * @var \Magento\Customer\Model\ResourceModel\Customer\CollectionFactory
     */
    private $customerCollecctionFactory;

    /**
     * ResendOtp constructor.
     * @param Validator $formKeyValidator
     * @param CustomerHelper $customerHelper
     * @param UrlInterface $url
     * @param CustomerFactory $customerFactory
     * @param AccountManagement $accountManagement
     * @param CollectionFactory $customerCollecctionFactory
     * @param Context $context
     */
    public function __construct(
        Validator $formKeyValidator,
        CustomerHelper $customerHelper,
        UrlInterface $url,
        CustomerFactory $customerFactory,
        AccountManagement $accountManagement,
        CollectionFactory $customerCollecctionFactory,
        \Magento\Framework\App\Action\Context $context
    )
    {
        $this->_formKeyValidator = $formKeyValidator;
        $this->_customerHelper = $customerHelper;
        $this->url = $url;
        $this->customerFactory = $customerFactory;
        $this->accountManagement = $accountManagement;
        $this->customerCollecctionFactory = $customerCollecctionFactory;
        return parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        if (!$this->_formKeyValidator->validate($this->getRequest())) {
            $result['status'] = "0";
            $result['error'] = "Form key is not valid nor expired";
        }
        $mobileNumber = $this->getRequest()->getParam("mobile_number");
        $email = $this->getEmailUsingMobile($mobileNumber);
        $id = $this->getRequest()->getParam("id");
        try {
            $this->accountManagement->initiatePasswordResetMobile(
                $mobileNumber,
                $email,
                AccountManagement::EMAIL_RESET,
                null
            );
            $result['status'] = "1";
        } catch (\Exception $e) {
            $result['status'] = "0";
            $result['error'] = $e->getMessage();
        }
        $this->getResponse()->representJson(
            $this->_objectManager->get('Magento\Framework\Json\Helper\Data')
                ->jsonEncode($result)
        );
    }

    public function getEmailUsingMobile($telephone)
    {
        $collection = $this->customerCollecctionFactory->create();
        $collection->addAttributeToSelect(['email', CustomerHelper::PHONE_NUMBER,CustomerHelper::OTP_VERIFIED,'entity_id'])
            ->addAttributeToFilter(CustomerHelper::PHONE_NUMBER,array('eq'=>"$telephone"))
            ->addAttributeToFilter(CustomerHelper::OTP_VERIFIED,array('eq'=>"1"))
            ->setPageSize(1)
            ->setCurPage(1);
        if($collection->count() >= 1 ){
            return $collection->getFirstItem()->getEmail();
        }
        return false;
    }
}
