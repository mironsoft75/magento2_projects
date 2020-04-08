<?php

namespace Codilar\Customer\Controller\Otp;

use Codilar\Customer\Helper\Otp\Transport;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Codilar\Customer\Helper\Data as CustomerHelper;

/**
 * Class Resend
 * @package Codilar\Customer\Controller\Otp
 */
class Resend extends \Magento\Customer\Controller\AbstractAccount{

    const RESEND_SUCCESSFUL_MESSAGE = "Otp resent successfully";
    const RESEND_FAILURE_MESSAGE = "Some error occurred while sending otp. Please try again later";
    /**
     * @var Transport
     */
    protected $_transport;
    /**
     * @var CustomerHelper
     */
    private $_customerHelper;

    /**
     * Resend constructor.
     * @param Transport $transport
     * @param CustomerHelper $customerHelper
     * @param Context $context
     */
    public function __construct(
        Transport $transport,
        CustomerHelper $customerHelper,
        Context $context
    )
    {
        parent::__construct($context);
        $this->_transport = $transport;
        $this->_customerHelper = $customerHelper;
    }

    public function execute()
    {
        $response = array(
            'status'    =>  true,
            'message'   =>  __(self::RESEND_SUCCESSFUL_MESSAGE)
        );
        $phoneNumber = $this->getPhoneNumber();
        $otp = mt_rand(100000, 999999);
        if($this->_customerHelper->isCustomerOtpVerifiedFromSession()){
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setData(['status' => false, 'message' => __("Number already verified")]);
            return $result;
        }
        else if(!$this->_customerHelper->isMobileNumberAvailable($phoneNumber)){
            $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
            $result->setData(['status' => false, 'message' => $this->getNumberAlreadyInUseMessage()]);
            return $result;
        }
        else if(!$this->_transport->sendSms($phoneNumber, $otp,"new_account")){
            $this->_customerHelper->setCustomerOtpToSession($otp);
            $this->_customerHelper->setCustomerPhoneNumberToSession($phoneNumber);
            $response['status'] = false;
            $response['message'] = __(self::RESEND_FAILURE_MESSAGE);
        }
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($response);
        return $result;
    }

    /**
     * @return mixed
     */
    public function getPhoneNumber(){
        $phoneNumber = $this->_customerHelper->getCustomerPhoneNumberFromSession();
        return $phoneNumber;
    }

    /**
     * @return \Magento\Framework\Phrase
     */
    protected function getNumberAlreadyInUseMessage(){
        $url = $this->_url->getUrl('customer/account/edit');
        return __("It appears the phone number you've registered is already in use. Please click <a href='$url'>Here</a> to change your registered mobile number");
    }

}