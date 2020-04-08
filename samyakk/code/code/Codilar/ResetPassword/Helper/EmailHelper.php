<?php

namespace Codilar\ResetPassword\Helper;

use Codilar\ResetPassword\Model\Config;
use Codilar\ResetPassword\Model\ResetPassword;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\View\LayoutInterface;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\ScopeInterface;

class EmailHelper extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_SENDER_MAIL = 'trans_email/ident_general/email';

    const XML_PATH_SENDER_NAME = 'trans_email/ident_general/email';

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;
    /**
     * @var Escaper
     */
    protected $escaper;
    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var LayoutInterface
     */
    private $layout;
    /**
     * @var ResetPassword
     */
    private $resetPassword;
    /**
     * @var Config
     */
    private $config;

    /**
     * Email constructor.
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param ResetPassword $resetPassword
     * @param LayoutInterface $layout
     * @param Config $config
     */
    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        ResetPassword $resetPassword,
        LayoutInterface $layout,
        Config $config
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->layout = $layout;
        $this->logger = $context->getLogger();
        $this->resetPassword = $resetPassword;
        $this->config = $config;
    }


    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     */
    public function sendConfirmEmail($customer)
    {
        try {
            $emailTemplateId = $this->config->getConfirmEmailTemplate();
            if ($emailTemplateId != '-1') {
                $templateVars = [
                    'customer' => $customer
                ];
                $this->sendMail($emailTemplateId, $templateVars, $customer->getEmail());
            }
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    /**
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     * @param $link
     */
    public function sendRequestMail($customer, $link) {
        try {
            $emailTemplateId = $this->config->getRequestEmailTemplate();
            if ($emailTemplateId != '-1') {
                $templateVars = [
                    'reset_link' => $link,
                    'customer' => $customer
                ];
                $this->sendMail($emailTemplateId, $templateVars, $customer->getEmail());
            }
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    public function sendMail($templateId, $templateVars, $sendTo) {
        $this->inlineTranslation->suspend();
        $sender = [
            'name' => $this->getSenderName(),
            'email' => $this->getSenderEmail(),
        ];
        /** @var \Magento\Framework\Mail\Template\TransportBuilder $transport */
        $transport = $this->transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                    'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom($sender);

        $transport->addTo($sendTo);
        try {
            $transport->getTransport()->sendMessage();
        } catch (MailException $e) {
            $this->logger->debug($e->getMessage());
        }
        $this->inlineTranslation->resume();
    }

    /**
     * @return string
     */
    public function getSenderEmail() {
        return $this->scopeConfig->getValue(self::XML_PATH_SENDER_MAIL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getSenderName(){
        return $this->scopeConfig->getValue(self::XML_PATH_SENDER_NAME, ScopeInterface::SCOPE_STORE);
    }
}