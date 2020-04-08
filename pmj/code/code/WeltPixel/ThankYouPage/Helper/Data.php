<?php
namespace WeltPixel\ThankYouPage\Helper;

/**
 * Class Data
 * @package WeltPixel\ThankYouPage\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var array
     */
    protected $_thankYouPageOptions;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    protected $productMetadata;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\ProductMetadataInterface $productMetadata
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata
    )
    {
        parent::__construct($context);
        $this->_thankYouPageOptions = $this->scopeConfig->getValue('weltpixel_thankyoupage', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->productMetadata = $productMetadata;
    }

    /**
     * @return boolean
     */
    public function isOrderDetailsEnabled()
    {
        return $this->_thankYouPageOptions['order_details']['enable'];
    }

    /**
     * @return string
     */
    public function showContinueShopping()
    {
        return $this->_thankYouPageOptions['order_details']['continue_shopping'];
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->_thankYouPageOptions['order_details']['title'];
    }

    /**
     * @return string
     */
    public function getPageSubTitle()
    {
        return $this->_thankYouPageOptions['order_details']['subtitle'];
    }

    /**
     * @return string
     */
    public function getOrderDescription()
    {
        return $this->_thankYouPageOptions['order_details']['description'];
    }

    /**
     * @return string
     */
    public function getOrderDetailsSortOrder()
    {
        return isset($this->_thankYouPageOptions['order_details']['sort_order']) ? $this->_thankYouPageOptions['order_details']['sort_order'] : 0;
    }

    /**
     * @return string
     */
    public function getOrderDetailTemplate()
    {
        if ($this->isOrderDetailsEnabled()) {
            return 'WeltPixel_ThankYouPage::success.phtml';
        }

        return '';
    }

    /**
     * @return string
     */
    public function getCreateAccountTemplate() {
        if ($this->isCreateAccountEnabled()) {
            return 'WeltPixel_ThankYouPage::registration.phtml';
        }

        return '';
    }

    /**
     * @return boolean
     */
    public function isCreateAccountEnabled()
    {
        return $this->_thankYouPageOptions['create_account']['enable'];
    }

    /**
     * @return string
     */
    public function getCreateAccountDescription()
    {
        return $this->_thankYouPageOptions['create_account']['description'];
    }

    /**
     * @return string
     */
    public function getCreateAccountEmailLabel()
    {
        return $this->_thankYouPageOptions['create_account']['email_label'];
    }

    /**
     * @return string
     */
    public function getCreateAccountAfterCreationLabel()
    {
        return $this->_thankYouPageOptions['create_account']['after_creation_message'];
    }

    /**
     * @return string
     */
    public function getCreateAccountSortOrder()
    {
        return isset($this->_thankYouPageOptions['create_account']['sort_order']) ? $this->_thankYouPageOptions['create_account']['sort_order'] : 0;
    }

    /**
     * @return boolean
     */
    public function isNewsletterSubscribeEnabled()
    {
        return $this->_thankYouPageOptions['newsletter_subscribe']['enable'];
    }

    /**
     * @return boolean
     */
    public function getNewsletterSubscribeDescription()
    {
        return $this->_thankYouPageOptions['newsletter_subscribe']['description'];
    }

    /**
     * @return boolean
     */
    public function getNewsletterSubscribeSortOrder()
    {
        return isset($this->_thankYouPageOptions['newsletter_subscribe']['sort_order']) ? $this->_thankYouPageOptions['newsletter_subscribe']['sort_order'] : 0;
    }


    /**
     * @return boolean
     */
    public function isCustomBlockEnabled()
    {
        return $this->_thankYouPageOptions['custom_block']['enable'];
    }

    /**
     * @return string
     */
    public function getCheckoutBlockId()
    {
        return $this->_thankYouPageOptions['custom_block']['block_id'];
    }

    /**
     * @return string
     */
    public function getCustomBlockSortOrder()
    {
        return isset($this->_thankYouPageOptions['custom_block']['sort_order']) ? $this->_thankYouPageOptions['custom_block']['sort_order'] : 0;
    }

    /**
     * @return array
     */
    public function getAvailableBlockElements()
    {
        $blocksForOutput = [];

        if ($this->isOrderDetailsEnabled()) {
            $blocksForOutput['checkout.success'] = $this->getOrderDetailsSortOrder();
        }

        if ($this->isCreateAccountEnabled()) {
            $blocksForOutput['checkout.registration'] = $this->getCreateAccountSortOrder();
        }

        if ($this->isCustomBlockEnabled()) {
            $blocksForOutput['weltpixel.checkout.block'] = $this->getCustomBlockSortOrder();
        }

        if ($this->isNewsletterSubscribeEnabled()) {
            $blocksForOutput['weltpixel.checkout.newsletter'] = $this->getNewsletterSubscribeSortOrder();
        }

        asort($blocksForOutput);

        return array_keys($blocksForOutput);
    }

    /**
     * @return string
     */
    public function getRegistrationTemplate()
    {
        $magentoVersion = $this->productMetadata->getVersion();
        $template = 'WeltPixel_ThankYouPage/registration';
        if (version_compare($magentoVersion, '2.2.6', '>=')) {
            $template = 'WeltPixel_ThankYouPage/registration-2-6';
        }
        return $template;
    }
}
