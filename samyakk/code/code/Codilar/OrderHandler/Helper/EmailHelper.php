<?php

namespace Codilar\OrderHandler\Helper;

use Codilar\OrderHandler\Model\Config;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\View\LayoutInterface;
use Psr\Log\LoggerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class EmailHelper
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
     * @var Config
     */
    private $config;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Email constructor.
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param LayoutInterface $layout
     * @param Config $config
     * @param LoggerInterface $logger
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        LayoutInterface $layout,
        Config $config,
        LoggerInterface $logger,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->layout = $layout;
        $this->logger = $logger;
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
    }


    /**
     * @param $order
     * @param \Magento\Customer\Api\Data\CustomerInterface $customer
     */
    public function sendOrderCancelEmail($order, $customer, $email)
    {
        try {
            $emailTemplateId = $this->config->getOrderCancelTemplate();
            if ($emailTemplateId != '-1') {
                $templateVars = [
                    'customer' => $customer,
                    'order' => $order
                ];
                $this->sendMail($emailTemplateId, $templateVars, $email);
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