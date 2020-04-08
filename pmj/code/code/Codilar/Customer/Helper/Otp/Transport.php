<?php

namespace Codilar\Customer\Helper\Otp;
use Codilar\Api\Helper\Emulator;
use Codilar\Customer\Helper\Sms\SmsHelper as SmsHelper;
use Codilar\Sms\Helper\Transport as SmsTransport;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class Transport
 * @package Codilar\Customer\Helper\Otp
 */
class Transport extends AbstractHelper
{
    /**
     * @var SmsHelper
     */
    protected $smsHelper;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Emulator
     */
    private $emulator;
    /**
     * @var SmsTransport
     */
    private $transport;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Transport constructor.
     * @param Context $context
     * @param SmsHelper $smsHelper
     * @param LoggerInterface $logger
     * @param Emulator $emulator
     * @param SmsTransport $transport
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        SmsHelper $smsHelper,
        LoggerInterface $logger,
        Emulator $emulator,
        SmsTransport $transport,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->smsHelper = $smsHelper;
        $this->logger = $logger;
        $this->emulator = $emulator;
        $this->transport = $transport;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $number
     * @param $data
     * @param null $messageType
     * @return array|bool
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendSms($number, $data,$messageType = null){
        try{
            $otp = [
                'otp' => $data
            ];
            $sendData = $this->getTemplate("Codilar_Sms::customer/otp.html",$otp);
            if($messageType){
                if($messageType == "reset"){
                    $sendData = $this->getTemplate("Codilar_Sms::customer/reset_otp.html",$otp);
                }
                elseif($messageType == "login"){
                    $sendData = $this->getTemplate("Codilar_Sms::customer/login_otp.html",$otp);
                }
                elseif($messageType == "edit"){
                    $sendData = $this->getTemplate("Codilar_Sms::customer/edit_otp.html",$otp);
                }
                elseif($messageType == "new_account"){
                    $sendData = $this->getTemplate("Codilar_Sms::customer/otp.html",$otp);
                }
            }

            $response = $this->smsHelper->sendSms($number, $sendData);
            $this->logger->info("OTP Log: Mobile Number-".$number.", Otp Data- ".json_encode($data));
            return $response;
        }
        catch (\Exception $e){

        }
    }

    /**
     * @param $template
     * @param array $data
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getTemplate($template, $data = []){
        $this->emulator->startEmulation(Emulator::AREA_FRONTEND, $this->storeManager->getStore()->getId());
        $template = $this->transport->getSmsTemplate($template, $data);
        $this->emulator->stopEmulation();
        return $template;
    }
}