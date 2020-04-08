<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 3/12/18
 * Time: 5:06 PM
 */

namespace Codilar\Videostore\Helper;


use Magento\Framework\Session\SessionManager as Session;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
class Otp extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Validator
     */
    private $formKeyValidator;

    /**
     * Otp constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param LoggerInterface $logger
     * @param Validator $formKeyValidator
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        LoggerInterface $logger,
        Validator $formKeyValidator
    )
    {
        parent::__construct($context);
        $this->customerSession = $customerSession;
        $this->logger = $logger;
        $this->formKeyValidator = $formKeyValidator;
    }

    /**
     * @return int
     */
    public function generateOtp(){
        $digits = 6;
        $otp =rand(pow(10, $digits-1), pow(10, $digits)-1);
        return $otp;
    }

    /**
     * @param int $otp
     * @param $mobile
     */
    public function setOtp($otp, $mobile)
    {
        $this->customerSession->setData( 'Videocart', [
            'Videocart_Otp' => $otp,
            'Videocart_Mobile' => $mobile
        ]);
    }

    /**
     * @return array
     */
    public function getOtp(){
        return $this->customerSession->getData('Videocart');
    }

    /**
     * @param int $mobile
     * @return array|bool
     */
    public function sendOtp($mobile)
    {
        $otp = $this->generateOtp();
        $this->setOtp($otp, $mobile);
        try{
            return $this->smshelper->sendSms($mobile, $this->createVideocartMessage($otp));
        }
        catch (\Exception $exception){
            $this->logger->error(__($exception->getMessage()));
        }
    }

    /**
     * @param int $otp
     * @param $mobile
     * @return bool
     */
    public function verifyOtp($otp, $mobile)
    {
        $otpDetails = $this->getOtp();
        if(!$otpDetails['Videocart_Otp']){
            return false;
        }
        if($otpDetails['Videocart_Otp'] == $otp && $otpDetails['Videocart_Mobile'] == $mobile){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * @param $mobile
     * @param $status
     * @return array|bool
     */
    public function sendStatusMessage($mobile, $status)
    {
        try{
            return $this->smshelper->sendSms($mobile, $this->createVideocartStatusMessage($status));
        }
        catch (\Exception $exception){
            $this->logger->error(__($exception->getMessage()));
        }
    }

    /**
     * @param $mobile
     * @return array|bool
     */
    public function sendRequestSubmitMessage($mobile)
    {
        try{
            return $this->smshelper->sendSms($mobile, $this->createRequestSubmitMessage());
        }
        catch (\Exception $exception){
            $this->logger->error(__($exception->getMessage()));
        }
    }

    public function createVideocartMessage($otp){
        $msg = "Dear customer, your otp for PMJ Jewels Video Session request is ".$otp;
        return $msg;
    }

    public function createVideocartStatusMessage($status){
        $msg = "Dear customer, your request for Video Session has been ".$status;
        return $msg;
    }

    public function createRequestSubmitMessage()
    {
        $msg = "Dear customer, your request for Video session with PMJ Jewels has been submitted";
        return $msg;
    }

    /**
     * @param $request
     * @return bool
     */
    public function formValidation($request){
        return $this->formKeyValidator->validate($request);
    }
}