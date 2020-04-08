<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/8/19
 * Time: 6:01 PM
 */

namespace Codilar\ComputePrice\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

/**
 * Class PriceHelper
 * @package Codilar\ComputePrice\Helper
 */
class PriceHelper extends AbstractHelper
{
    const XML_PATH_SEND_MAIL = 'codilar_mail/settings/send_mail';
    const XML_PATH_SENDER_NAME = 'codilar_mail/settings/sender_name';
    const XML_PATH_SENDER_EMAIL = 'codilar_mail/settings/sender_email';
    const XML_PATH_RECEIVER_EMAIL = 'codilar_mail/settings/receiver_email';

    /**
     * PriceHelper constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);
    }
    /**
     * Send Mail
     *
     * @return bool
     */
    public function sendMail()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SEND_MAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
    /**
     * Sender Name
     *
     * @return mixed
     */
    public function senderName()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SENDER_NAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Sender Email
     *
     * @return mixed
     */
    public function senderEmail()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SENDER_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Receiver Email
     * @return mixed
     */
    public function receiverEmail()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_RECEIVER_EMAIL,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}