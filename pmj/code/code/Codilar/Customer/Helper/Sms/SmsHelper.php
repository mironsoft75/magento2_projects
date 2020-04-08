<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26/2/18
 * Time: 12:23 PM
 */

namespace Codilar\Customer\Helper\Sms;


use Codilar\Sms\Helper\Transport;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class SmsHelper extends AbstractHelper
{
    /**
     * @var Transport
     */
    private $smsHelper;

    /**
     * SmsHelper constructor.
     * @param Context $context
     * @param Transport $smsHelper
     */
    public function __construct(
        Context $context,
        Transport $smsHelper
    )
    {
        parent::__construct($context);
        $this->smsHelper = $smsHelper;
    }

    /**
     * @param $number
     * @param $data
     * @return array|bool
     */
    public function sendSms($number, $data){
        return $this->smsHelper->sendSms($number, $data);
    }
}