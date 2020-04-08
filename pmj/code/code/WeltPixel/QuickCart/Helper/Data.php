<?php

namespace WeltPixel\QuickCart\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
	/**
	 * @var \Magento\Store\Model\StoreManagerInterface
	 */
	protected $_storeManager;
	
	/**
	 * @var array
	 */
	protected $_quickcartOptions;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
	public function __construct(
			\Magento\Framework\App\Helper\Context $context,
			\Magento\Store\Model\StoreManagerInterface $storeManager
	) {
		parent::__construct($context);

		$this->_storeManager = $storeManager;
		$this->_quickcartOptions = $this->scopeConfig->getValue('weltpixel_quick_cart', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
	}

    /**
     * Check if QuickCart is enabled
     *
     * @return mixed
     */
	public function quicartIsEnabled()
    {
        return $this->scopeConfig->getValue(
            'weltpixel_quick_cart/general/enable',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $this->getStoreId());
    }

    /**
     * Check if should open mini-cart after an item was added
     *
     * @return mixed
     */
    public function openMinicart()
    {
        if ($this->quicartIsEnabled()) {
            return $this->scopeConfig->getValue(
                'weltpixel_quick_cart/general/open_minicart',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $this->getStoreId());
        }

        return false;
    }
	
	/**
	 * @return int
	 */
	public function getStoreId() {
		return $this->_storeManager->getStore()->getId();
	}
	
	/**
	 * @param int $storeId
	 * @return mixed
	 */
	public function getHeaderHeight($storeId = 0) {
		if ($storeId) {
			return $this->scopeConfig->getValue('weltpixel_quick_cart/header/header_height', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		} else {
			return $this->_quickcartOptions['header']['header_height'];
		}
	}
	
	/**
	 * @param int $storeId
	 * @return mixed
	 */
	public function getHeaderBackground($storeId = 0) {
		if ($storeId) {
			return $this->scopeConfig->getValue('weltpixel_quick_cart/header/header_background', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		} else {
			return $this->_quickcartOptions['header']['header_background'];
		}
	}
	
	/**
	 * @param int $storeId
	 * @return mixed
	 */
	public function getHeaderTextColor($storeId = 0) {
		if ($storeId) {
			return $this->scopeConfig->getValue('weltpixel_quick_cart/header/header_text_color', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		} else {
			return $this->_quickcartOptions['header']['header_text_color'];
		}
	}
	
	/**
	 * @param int $storeId
	 * @return mixed
	 */
	public function getSubtotalBackground($storeId = 0) {
		if ($storeId) {
			return $this->scopeConfig->getValue('weltpixel_quick_cart/footer/subtotal_background', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		} else {
			return $this->_quickcartOptions['footer']['subtotal_background'];
		}
	}
	
	/**
	 * @param int $storeId
	 * @return mixed
	 */
	public function getSubtotalTextColor($storeId = 0) {
		if ($storeId) {
			return $this->scopeConfig->getValue('weltpixel_quick_cart/footer/subtotal_text_color', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
		} else {
			return $this->_quickcartOptions['footer']['subtotal_text_color'];
		}
	}
}
