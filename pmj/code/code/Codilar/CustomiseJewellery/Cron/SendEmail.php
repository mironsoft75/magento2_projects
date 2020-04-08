<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 21/1/19
 * Time: 7:09 PM
 */

namespace Codilar\CustomiseJewellery\Cron;

use \Psr\Log\LoggerInterface;
use Codilar\CustomiseJewellery\Model\ResourceModel\CustomiseJewellery\Collection;
use Codilar\Sms\Helper\Transport;
use Magento\Framework\Escaper;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Catalog\Api\ProductRepositoryInterface;

/**
 * Class SendEmail
 * @package Codilar\CustomiseJewellery\Cron
 */
class SendEmail
{

    const REQUEST_TYPE1 = 'upload_any_design';
    const REQUEST_TYPE2 = 'customize_existing_design';
    const REQUEST_TYPE3 = 'work_with_our_designer';
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var Collection
     */
    protected $_customiseJewelleryCollection;
    /**
     * @var \Codilar\CustomiseJewellery\Model\CustomiseJewellery
     */
    private $customiseJewelleryModel;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $date;
    /**
     * @var Transport
     */
    protected $_helper;
    /**
     * @var Escaper
     */
    protected $_escaper;
    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;
    /**
     * @var StateInterface
     */
    protected $_inlineTranslation;
    /**
     * @var TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var ProductRepositoryInterface
     */
    protected $_productRepository;

    /**
     * SendEmail constructor.
     * @param LoggerInterface $logger
     * @param Collection $customiseJewelleryCollection
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param Transport $helper
     * @param Escaper $escaper
     * @param ScopeConfigInterface $scopeConfig
     * @param StateInterface $inlineTranslation
     * @param TransportBuilder $transportBuilder
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ProductRepositoryInterface $productRepository
     * @param \Codilar\CustomiseJewellery\Model\CustomiseJewellery $customiseJewelleryModel
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        Collection $customiseJewelleryCollection,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        Transport $helper,
        Escaper $escaper,
        ScopeConfigInterface $scopeConfig,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ProductRepositoryInterface $productRepository,
        \Codilar\CustomiseJewellery\Model\CustomiseJewellery $customiseJewelleryModel

    )
    {
        $this->_logger = $logger;
        $this->_customiseJewelleryCollection = $customiseJewelleryCollection;
        $this->_helper = $helper;
        $this->_escaper = $escaper;
        $this->_scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->_logger = $logger;
        $this->_inlineTranslation = $inlineTranslation;
        $this->_storeManager = $storeManager;
        $this->_productRepository = $productRepository;
        $this->customiseJewelleryModel = $customiseJewelleryModel;
    }

    public function execute()
    {
        $this->_customiseJewelleryCollection->addFieldToFilter('send_email', ['eq' => 1]);
        $this->_customiseJewelleryCollection->addFieldToFilter('email_sent', ['eq' => 0]);
        foreach ($this->_customiseJewelleryCollection->getItems() as $item) {
            $this->Send($item);
        }
    }

    /**
     * @param $item
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function Send($item)
    {
        $mobileNumber = $item->getData('mobile_number');
        $customerName = $item->getData('name');
        $customerEmail = $item->getData('email');
        $newRequestStatus = $item->getData('status');
        $requestType = $item->getData('request_type');
        $details = $item->getData('details');


        if ($requestType == self::REQUEST_TYPE1) {
            $imagePath = $item->getData('image_path');
            $mediaPath = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $completeImagePath = ($mediaPath . 'custom_image' . $imagePath);
        }

        if ($requestType == self::REQUEST_TYPE2) {
            $sku = $item->getData('product_sku');
            try {
                $product = $this->_productRepository->get($sku);
                $productName = $product->getName();
            } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
                $this->_logger->debug($e->getMessage());

            }

        }


        /** 0,1 ,2 3,4 shows respective request status */
        switch ($newRequestStatus) {
            case '0':
                if ($requestType == self::REQUEST_TYPE1) {
                    $displayMessage = __("Your request for Customised Jewellery is Submitted for the following design:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Submitted. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE2) {
                    $displayMessage = __("Your request for Customised Jewellery is Submitted for the Product:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Submitted for the Product: $productName. The details submitted by you are: $details Thank You.");

                }
                if ($requestType == self::REQUEST_TYPE3) {
                    $displayMessage = __("Your request for Customised Jewellery is Submitted.");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Submitted. The details submitted by you are: $details Thank You.");

                }
                break;
            case '1':
                if ($requestType == self::REQUEST_TYPE1) {
                    $displayMessage = __("Your request for Customised Jewellery is Pending for the following design:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Pending. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE2) {
                    $displayMessage = __("Your request for Customised Jewellery is Pending for the Product:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Pending for the Product: $productName. The details submitted by you are: $details Thank You.");

                }
                if ($requestType == self::REQUEST_TYPE3) {
                    $displayMessage = __("Your request for Customised Jewellery is Pending.");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Submitted. The details Pending by you are: $details Thank You.");

                }
                break;
            case '2':
                if ($requestType == self::REQUEST_TYPE1) {
                    $displayMessage = __("Your request for Customised Jewellery is Processing for the following design:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Processing. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE2) {
                    $displayMessage = __("Your request for Customised Jewellery is Processing for the Product:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Processing for the Product: $productName. The details submitted by you are: $details Thank You.");

                }
                if ($requestType == self::REQUEST_TYPE3) {
                    $displayMessage = __("Your request for Customised Jewellery is Processing.");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Processing. The details submitted by you are: $details Thank You.");

                }
                break;
            case '3':
                if ($requestType == self::REQUEST_TYPE1) {
                    $displayMessage = __("Your request for Customised Jewellery is Completed for the following design:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Completed. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE2) {
                    $displayMessage = __("Your request for Customised Jewellery is Completed for the Product:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Completed for the Product: $productName. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE3) {
                    $displayMessage = __("Your request for Customised Jewellery is Completed.");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Completed. The details submitted by you are: $details Thank You.");
                }
                break;
            case '4':
                if ($requestType == self::REQUEST_TYPE1) {
                    $displayMessage = __("Your request for Customised Jewellery is Cancelled for the following design:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Cancelled. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE2) {
                    $displayMessage = __("Your request for Customised Jewellery is Cancelled for the Product:");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Cancelled for the Product: $productName. The details submitted by you are: $details Thank You.");
                }
                if ($requestType == self::REQUEST_TYPE3) {
                    $displayMessage = __("Your request for Customised Jewellery is Cancelled.");
                    $mobileDisplayMessage = __("Hi $customerName, Request for your Customised Jewellery is Cancelled. The details submitted by you are: $details Thank You.");
                }
                break;
            default:

        }
        if ($mobileDisplayMessage) {
            $this->_helper->sendSms(($this->_escaper->escapeHtml($mobileNumber)), $mobileDisplayMessage);
        }
        try {
            $this->_inlineTranslation->suspend();
            $sender = [
                'name' => $this->getSenderName(),
                'email' => $this->getSenderEmail(),
            ];
            if ($requestType == self::REQUEST_TYPE1) {
                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('customise_jewellery_confirmation_mail_upload')
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars([
                        'customerName' => $customerName,
                        'customerEmail' => $customerEmail,
                        'displayMessage' => $displayMessage,
                        'details' => $details,
                        'completeImagePath' => $completeImagePath
                    ])
                    ->setFrom($sender)
                    ->addTo($this->_escaper->escapeHtml($customerEmail))->getTransport();
            } elseif ($requestType == self::REQUEST_TYPE2) {
                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('customise_jewellery_confirmation_mail_customise')
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars([
                        'customerName' => $customerName,
                        'customerEmail' => $customerEmail,
                        'displayMessage' => $displayMessage,
                        'productName' => $productName,
                        'details' => $details
                    ])
                    ->setFrom($sender)
                    ->addTo($this->_escaper->escapeHtml($customerEmail))->getTransport();
            } elseif ($requestType == self::REQUEST_TYPE3) {
                $transport = $this->_transportBuilder
                    ->setTemplateIdentifier('customise_jewellery_confirmation_mail_work_with_our_designers')
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars([
                        'customerName' => $customerName,
                        'customerEmail' => $customerEmail,
                        'displayMessage' => $displayMessage,
                        'details' => $details
                    ])
                    ->setFrom($sender)
                    ->addTo($this->_escaper->escapeHtml($customerEmail))->getTransport();
            }

            $transport->sendMessage();
            $this->_inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->_logger->debug($e->getMessage());
        }
        $item->setSendEmail(0);
        $item->setEmailSent(1);
        $item->save();

    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        $senderName = $this->_scopeConfig->getValue("trans_email/ident_general/name");
        return $senderName;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        $senderEmail = $this->_scopeConfig->getValue("trans_email/ident_general/email");
        return $senderEmail;
    }
}