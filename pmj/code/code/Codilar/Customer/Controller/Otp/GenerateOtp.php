<?php

namespace Codilar\Customer\Controller\Otp;

use Codilar\Customer\Helper\Otp\Transport;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Codilar\Customer\Helper\Data as CustomerHelper;

/**
 * Class GenerateOtp
 * @package Codilar\Customer\Controller\Otp
 */
class GenerateOtp extends \Magento\Framework\App\Action\Action
{
    const SUCCESSFUL_MESSAGE = "OTP sent";
    const RESEND_SUCCESSFUL_MESSAGE = "Otp resent successfully";
    const RESEND_FAILURE_MESSAGE = "Some error occurred while sending otp. Please try again later";
    const NUMBER_NOT_FOUND = "Mobile Number Doesn't exists in our database";
    /**
     * @var Transport
     */
    protected $_transport;
    /**
     * @var CustomerHelper
     */
    protected $_customerHelper;

    /**
     * GenerateOtp constructor.
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

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute()
    {
        $phoneNumber = $this->getRequest()->getParam('phone_number');
        $response = array(
            'status'    =>  true,
            'message'   =>  __(self::SUCCESSFUL_MESSAGE)
        );
        if(!$this->isMobileNumberExists($phoneNumber)){
            $this->_customerHelper->setCustomerPhoneNumberToSession($this->getRequest()->getParam('phone_number'));
            $otp = mt_rand(100000, 999999);
            $this->_customerHelper->setCustomerOtpToSession($otp);
            if(!$this->_transport->sendSms($phoneNumber, $otp,"login")){
                $response['status'] = true;
                $response['message']   =  __(self::SUCCESSFUL_MESSAGE);
            }
        }
        else{
            $response = array(
                'status'    =>  false,
                'message'   =>  __(self::NUMBER_NOT_FOUND)
            );
        }
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($response);
        return $result;
    }

    /**
     * @param $telephone
     * @return bool
     */
    public function isMobileNumberExists($telephone)
    {
        return $this->_customerHelper->isMobileNumberAvailable($telephone);
    }
}