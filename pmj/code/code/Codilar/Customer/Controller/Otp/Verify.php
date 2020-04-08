<?php

namespace Codilar\Customer\Controller\Otp;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Codilar\Customer\Helper\Login as LoginHelper;
use Codilar\Customer\Helper\Data as CustomerHelper;

/**
 * Class Verify
 * @package Codilar\Customer\Controller\Otp
 */
class Verify extends \Magento\Customer\Controller\AbstractAccount{

    const VALIATION_SUCCESSFUL_MESSAGE = "Verification Successful";
    const VALIDATION_FAILURE_MESSAGE = "Verification failure";
    /**
     * @var LoginHelper
     */
    private $loginHelper;
    /**
     * @var CustomerHelper
     */
    private $_customerHelper;

    /**
     * Verify constructor.
     * @param CustomerHelper $customerHelper
     * @param Context $context
     * @param LoginHelper $loginHelper
     */
    public function __construct(
        CustomerHelper $customerHelper,
        Context $context,
        LoginHelper $loginHelper
    )
    {
        parent::__construct($context);
        $this->loginHelper = $loginHelper;
        $this->_customerHelper = $customerHelper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $afterLoginUrl = $this->loginHelper->getAfterLoginUrl();
        $phoneNumber = $this->_customerHelper->getCustomerPhoneNumberFromSession();
        if(!$this->_customerHelper->isMobileNumberAvailable($phoneNumber)){
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setData(['status' => false, 'message' => $this->getNumberAlreadyInUseMessage()]);
            return $result;
        }
        $otp = $this->getRequest()->getPost('otp');
        $response = array(
            'status'    =>  false,
            'message'   =>  __(self::VALIDATION_FAILURE_MESSAGE)
        );
        if($this->_customerHelper->isCustomerOtpVerifiedFromSession() == CustomerHelper::STATUS_VERIFIED){
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setData(['status' => false, 'message' => __("Number already verified")]);
            return $result;
        }
        else if(!empty($otp) && $this->_customerHelper->validateOtp($otp)){
            $response['status'] = true;
            $response['message'] = __(self::VALIATION_SUCCESSFUL_MESSAGE);
            $response['after_login_url'] = $afterLoginUrl;
            $this->_customerHelper->setOtpVerifiedForCustomer($this->_customerHelper->getCustomerIdFromSession());
            $this->messageManager->addSuccess("Phone number Verified successfully.");
        }
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($response);
        return $result;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getNumberAlreadyInUseMessage(){
        $url = $this->_url->getUrl('customer/account/edit');
        return __("It appears the phone number you've registered is already in use. Please click <a href='$url'>Here</a> to change your registered mobile number");
    }


}