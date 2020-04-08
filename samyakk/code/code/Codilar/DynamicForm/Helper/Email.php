<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Helper;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface as InlineTranslation;
use Magento\Store\Model\ScopeInterface;

class Email
{
    /**
     * @var TransportBuilder
     */
    private $transportBuilder;
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var InlineTranslation
     */
    private $inlineTranslation;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Email constructor.
     * @param TransportBuilder $transportBuilder
     * @param Escaper $escaper
     * @param InlineTranslation $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TransportBuilder $transportBuilder,
        Escaper $escaper,
        InlineTranslation $inlineTranslation,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->escaper = $escaper;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param string $emailTemplate
     * @param string $sender
     * @param string $toEmail
     * @param string $toName
     * @param array $data
     * @throws \Magento\Framework\Exception\MailException
     */
    public function sendEmail($emailTemplate, $sender, $toEmail, $toName = '', $data = [])
    {
        if ($emailTemplate && $emailTemplate !== \Codilar\DynamicForm\Model\Config\Source\EmailTemplate::OPTION_NO_EMAIL) {
            $senderEmail = $this->scopeConfig->getValue('trans_email/ident_' . $sender . '/email', ScopeInterface::SCOPE_STORE);
            $senderName = $this->scopeConfig->getValue('trans_email/ident_' . $sender . '/name', ScopeInterface::SCOPE_STORE);
            if ($senderName && $senderEmail) {
                $this->inlineTranslation->suspend();
                $sender = [
                    'name' => $this->escaper->escapeHtml($senderName),
                    'email' => $this->escaper->escapeHtml($senderEmail),
                ];
                $transport = $this->transportBuilder
                    ->setTemplateIdentifier($emailTemplate)
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars($data)
                    ->setFrom($sender)
                    ->addTo($toEmail, $toName)
                    ->getTransport();
                $transport->sendMessage();
                $this->inlineTranslation->resume();
            }
        }
    }
}