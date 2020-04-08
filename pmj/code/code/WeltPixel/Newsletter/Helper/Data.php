<?php

namespace WeltPixel\Newsletter\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const COOKIE_NAME = 'weltpixel_newsletter';

    /**
     * \Magento\Cookie\Helper\Cookie
     */
    protected $cookieHelper;

    /**
     * @var array
     */
    protected $_newsletterOptions;

    /** @var \WeltPixel\MobileDetect\Helper\Data */
    protected $_mobileDetectHelper;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \WeltPixel\MobileDetect\Helper\Data $mobileDetectHelper
     * @param \Magento\Cookie\Helper\Cookie $cookieHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \WeltPixel\MobileDetect\Helper\Data $mobileDetectHelper,
        \Magento\Cookie\Helper\Cookie $cookieHelper
    ) {
        parent::__construct($context);
        $this->_newsletterOptions = $this->scopeConfig->getValue('weltpixel_newsletter', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $this->_mobileDetectHelper = $mobileDetectHelper;
        $this->cookieHelper = $cookieHelper;
    }

    /**
     * @return boolean
     */
    public function isEnabled() {
        return !$this->cookieHelper->isUserNotAllowSaveCookie() && $this->_newsletterOptions['general']['enable'];
    }

    /**
     * @return integer
     */
    public function getDisplayMode() {
        return $this->_newsletterOptions['general']['display_mode'];
    }

    /**
     * @return string
     */
    public function getDisplayBlock() {
        return $this->_newsletterOptions['general']['display_block'];
    }

    /**
     * @return integer
     */
    public function getVisitedPages() {
        return $this->_newsletterOptions['general']['display_after_pages'];
    }

    /**
     * @return integer
     */
    public function getSecondsToDisplay() {
        return $this->_newsletterOptions['general']['display_after_seconds'];
        }

    /**
     * @return boolean
     */
    public function displayOnMobile() {
        return $this->_newsletterOptions['general']['display_mobile'];
    }

    /**
     * @return string
     */
    public function getCloseOption() {
        return $this->_newsletterOptions['general']['disable_popup'];
    }

    /**
     * @return integer
     */
    public function getLifeTime() {
        return $this->_newsletterOptions['general']['popup_cookie_lifetime'];
    }

    /**
     * @return string
     */
    public function getCookieName() {
        return self::COOKIE_NAME;
    }

    /**
     * @param bool $justCountPages
     * @return bool
     */
    public function canShowPopup($justCountPages = false) {
        $NisAjax = !$this->_request->isAjax();;
        $enabled = $this->isEnabled();
        $dOption = $this->getDisplayMode();
        //check if you are on home page
        $weAreOnHomePage = ($this->_getUrl('') == $this->_getUrl('*/*/*', array('_current' => true, '_use_rewrite' => true))) ? 1 : 0;
        $displayOnMobile = $this->displayOnMobile();
        $canShowOnMobile = true;
        if (!$displayOnMobile && $this->_mobileDetectHelper->isMobile()) :
            $canShowOnMobile = false;
        endif;

        if (!$justCountPages) {
            if ($dOption == \WeltPixel\Newsletter\Model\Config\Source\DisplayMode::MODE_ALL_PAGES) {
                return ( $enabled && $NisAjax && $canShowOnMobile );
            } else {
                //check if you are on home page
                return ( $enabled && $NisAjax && $weAreOnHomePage && $canShowOnMobile );
            }
        } else {
            return ( $enabled && $NisAjax && $canShowOnMobile );
        }
    }

    /**
     * @return string
     */
    public function getFormActionUrl() {
        return $this->_getUrl('newsletter/subscriber/new', array('_secure' => true));
    }

    /**
     * @return boolean
     */
    public function isRequestAjax() {
        return $this->_getRequest()->isAjax();
    }

    /**
     * @return bool
     */
    public function isTermsConditionsEnabled() {
        return $this->_newsletterOptions['general']['terms_conditions_consent'];
    }

    /**
     * @return string
     */
    public function getTermsConditionsText() {
        return $this->_newsletterOptions['general']['terms_conditions_text'];
    }

    /**
     * @return bool
     */
    public function isTermsConditionsCheckboxRequired() {
        return $this->_newsletterOptions['general']['terms_conditions_checkbox'];
    }

}
