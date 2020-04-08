<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 29/11/18
 * Time: 2:50 PM
 */

namespace Codilar\Videostore\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Psr\Log\LoggerInterface;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
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
     * Email constructor.
     * @param Context $context
     * @param StateInterface $inlineTranslation
     * @param Escaper $escaper
     * @param TransportBuilder $transportBuilder
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
        $this->scopeConfig = $scopeConfig;
    }
    /**
     * FUNCTION TO send an email to customer on request submit
     * @param $formData
     */
    public function sendEmailOnCustomerVerification($formData)
    {
        $tryonTime = (array_key_exists('tryonTime', $formData)) ? $formData['tryonTime'] : "NA";
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->getSenderName(),
                'email' => $this->getSenderEmail(),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('videostore_request_template')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'customerName' => $formData['fullname'] ,
                    'customerEmail'=> $formData['email'],
                    'sessionDate' => $formData['tryonDate'],
                    'sessionTime' => $tryonTime
                ])
                ->setFrom($sender)
                ->addTo($formData['email'])->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    public function sendMailAboutStatus($status, $email, $customerName, $productIds)
    {
        try {
            $this->inlineTranslation->suspend();
            $sender = [
                'name' => $this->getSenderName(),
                'email' => $this->getSenderEmail(),
            ];
            $transport = $this->transportBuilder
                ->setTemplateIdentifier('videostore_request_status')
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'customerName' => $customerName,
                    'requestStatus' => $status,
                    'videorequestProductIds' => $productIds
                ])
                ->setFrom($sender)
                ->addTo($email)->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
    /**
     * @return string
     */
    public function getSenderEmail(){
        $senderName = $this->scopeConfig->getValue("trans_email/ident_general/name");
        return $senderName;
    }

    /**
     * @return string
     */
    public function getSenderName(){
        $senderEmail = $this->scopeConfig->getValue("trans_email/ident_general/email");
        return $senderEmail;
    }

}