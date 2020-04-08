<?php

namespace WeltPixel\Quickview\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var array
     */
    protected $_quickviewOptions;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    ) {
        parent::__construct($context);
        $this->_quickviewOptions = $this->scopeConfig->getValue('weltpixel_quickview', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getSkuTemplate() {
        $removeSku = $this->_quickviewOptions['general']['remove_sku'];
        if (!$removeSku) {
            return 'Magento_Catalog::product/view/attribute.phtml';
        }

        return '';
    }

    /**
     * @return bool
     */
    public function isEnabled() {
        return trim($this->_quickviewOptions['general']['enable_product_listing']);
    }


    /**
     * @return string
     */
    public function getCustomCSS() {
        return trim($this->_quickviewOptions['general']['custom_css']);
    }

    /**
     * @return int
     */
    public function getCloseSeconds() {
        return trim($this->_quickviewOptions['general']['close_quickview']);
    }

    /**
     * @return boolean
     */
    public function getScrollAndOpenMiniCart() {
        return $this->_quickviewOptions['general']['scroll_to_top'];
    }

    /**
     * @return boolean
     */
    public function getShoppingCheckoutButtons() {
        return $this->_quickviewOptions['general']['enable_shopping_checkout_product_buttons'];
    }

    /**
     * @return string
     */
    public function getQuickViewType() {
        return  $this->_quickviewOptions['general']['quickview_type'];
    }

    /**
     * @return boolean
     */
    public function getCloseOnBgClick() {
        return  (boolean)$this->_quickviewOptions['general']['close_on_bgclick'];
    }

    /**
     * @return string
     */
    public function getZoomType() {
        return  (boolean)$this->_quickviewOptions['general']['zoom_eventtype'];
    }

    /**
     * @return string
     */
    public function getVersionTemplate() {
        $template = 'WeltPixel_Quickview::version/simple_popup.phtml';
        $quickViewType = $this->getQuickViewType();
        if ($quickViewType == \WeltPixel\Quickview\Model\Config\Source\QuickviewType::TYPE_RIGHT_SLIDE) {
            $template = 'WeltPixel_Quickview::version/right_fadein_popup.phtml';
        }
        if ($quickViewType == \WeltPixel\Quickview\Model\Config\Source\QuickviewType::TYPE_LEFT_SLIDE) {
            $template = 'WeltPixel_Quickview::version/left_fadein_popup.phtml';
        }

        return $template;
    }

}
