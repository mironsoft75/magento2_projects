<?php

namespace WeltPixel\CategoryPage\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    
    /**
     * @param int $storeId
     * @return mixed
     */
    public function displayReviews($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/review/display_reviews', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function displayAddToWishlist($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/display_wishlist', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function displayAddToCompare($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/display_compare', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function displaySwatches($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/display_swatches', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function displaySwatchTooltip($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/display_swatch_tooltip', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function isHoverImageEnabled($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/image/enable_hover_image', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function displayAddToCart($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/display_addtocart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function alignAddToCart($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/addtocart_align', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function getProductsPerLine($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/general/products_per_line', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getLayeredNavigationSwatchOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/swatch_layerednavigation', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getProductListingSwatchOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/swatch_productlisting', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getProductListingItemOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/item', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getProductListingNameOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/name', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getProductListingPriceOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/price', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getProductListingReviewOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/review', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param int $storeId
     * @return array
     */
    public function getToolbarOptions($storeId = null) {
        return $this->scopeConfig->getValue('weltpixel_category_page/toolbar', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
    }
}
