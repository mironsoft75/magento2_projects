<?php
/**
 * SocialShare
 *
 * @package     Ulmod_SocialShare
 * @author      Ulmod <support@ulmod.com>
 * @copyright   Copyright (c) 2016 Ulmod (http://www.ulmod.com/)
 * @license     http://www.ulmod.com/license-agreement.html
 */
 
namespace Ulmod\SocialShare\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Request\Http as RequestHttp;

class Data extends AbstractHelper
{
    /**
     * Path to store config of Social Share
     *
     * @var string|int
     */
    const XML_PATH_GENERAL_ENABLED = 'umsocialshare/general/enabled';
    const XML_PATH_GENERAL_PUBID_ID = 'umsocialshare/general/pubid_id';
    
    const XML_PATH_SERVICE_DISPLAY_SERVICES = 'umsocialshare/service/display_services';
    const XML_PATH_SERVICE_DISPLAY_WHATSAPP = 'umsocialshare/service/display_whatsapp';
    const XML_PATH_SERVICE_DISPLAY_FACEBOOK = 'umsocialshare/service/display_facebook';
    const XML_PATH_SERVICE_DISPLAY_TWITTER = 'umsocialshare/service/display_twitter';
    const XML_PATH_SERVICE_DISPLAY_LINKEDIN = 'umsocialshare/service/display_linkedin';
    const XML_PATH_SERVICE_DISPLAY_GOOGLEPLUS = 'umsocialshare/service/display_googleplus';
    const XML_PATH_SERVICE_DISPLAY_PINTEREST = 'umsocialshare/service/display_pinterest';
    const XML_PATH_SERVICE_DISPLAY_EMAIL = 'umsocialshare/service/display_email';
    const XML_PATH_SERVICE_DISPLAY_COMPACT = 'umsocialshare/service/display_compact';
    const XML_PATH_SERVICE_DISPLAY_COUNTER = 'umsocialshare/service/display_counter';
    
    const XML_PATH_PAGE_HOME = 'umsocialshare/page/home/display/home_page';
    const XML_PATH_PAGE_HOME_POSITION = 'umsocialshare/page/home/display/home_page_position';
    const XML_PATH_PAGE_HOME_STYLE = 'umsocialshare/page/home/display/home_page_style';
    const XML_PATH_PAGE_HOME_MOBILE = 'umsocialshare/page/home/display/home_page_mobile';
    const XML_PATH_PAGE_HOME_MOBILE_MAXWIDTH = 'umsocialshare/page/home/display/home_page_mobile_maxwidth';
    const XML_PATH_PAGE_CMS = 'umsocialshare/page/cms/display/cms_page';
    const XML_PATH_PAGE_CMS_POSITION = 'umsocialshare/page/cms/display/cms_page_position';
    const XML_PATH_PAGE_CMS_STYLE = 'umsocialshare/page/cms/display/cms_page_style';
    const XML_PATH_PAGE_CMS_MOBILE = 'umsocialshare/page/cms/display/cms_page_mobile';
    const XML_PATH_PAGE_CMS_MOBILE_MAXWIDTH = 'umsocialshare/page/cms/display/cms_page_mobile_maxwidth';
    const XML_PATH_PAGE_CAT  = 'umsocialshare/page/category/display/category_page';
    const XML_PATH_PAGE_CAT_POSITION = 'umsocialshare/page/category/display/category_page_position';
    const XML_PATH_PAGE_CAT_STYLE = 'umsocialshare/page/category/display/category_page_style';
    const XML_PATH_PAGE_CAT_MOBILE = 'umsocialshare/page/category/display/category_page_mobile';
    const XML_PATH_PAGE_CAT_MOBILE_MAXWIDTH   = 'umsocialshare/page/category/display/category_page_mobile_maxwidth';
    const XML_PATH_PAGE_PROD = 'umsocialshare/page/product/display/product_page';
    const XML_PATH_PAGE_PROD_POSITION = 'umsocialshare/page/product/display/product_page_position';
    const XML_PATH_PAGE_PROD_STYLE = 'umsocialshare/page/product/display/product_page_style';
    const XML_PATH_PAGE_PROD_MOBILE = 'umsocialshare/page/product/display/product_page_mobile';
    const XML_PATH_PAGE_PROD_MOBILE_MAXWIDTH = 'umsocialshare/page/product/display/product_page_mobile_maxwidth';
    const XML_PATH_PAGE_ADDI = 'umsocialshare/page/additional/display/additional_page';
    const XML_PATH_PAGE_ADDI_PAGE_LINKS = 'umsocialshare/page/additional/display/links';
    const XML_PATH_PAGE_ADDI_POSITION  = 'umsocialshare/page/additional/display/additional_page_position';
    const XML_PATH_PAGE_ADDI_STYLE = 'umsocialshare/page/additional/display/additional_page_style';
    const XML_PATH_PAGE_ADDI_MOBILE = 'umsocialshare/page/additional/display/additional_page_mobile';
    const XML_PATH_PAGE_ADDI_MOBILE_MAXWIDTH = 'umsocialshare/page/additional/display/additional_page_mobile_maxwidth';

    const XML_PATH_PAGE_HOME_DESIGN_WHATSAPP_BGCOLOR = 'umsocialshare/page/home/design/whatsapp_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_FACEBOOK_BGCOLOR = 'umsocialshare/page/home/design/facebook_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_TWITTER_BGCOLOR = 'umsocialshare/page/home/design/twitter_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_LINKEDIN_BGCOLOR = 'umsocialshare/page/home/design/linkedin_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_GOOGLEPLUS_BGCOLOR = 'umsocialshare/page/home/design/googleplus_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_PINTEREST_BGCOLOR = 'umsocialshare/page/home/design/pinterest_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_EMAIL_BGCOLOR = 'umsocialshare/page/home/design/email_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_COMPACT_BGCOLOR = 'umsocialshare/page/home/design/compact_bgcolor';
    const XML_PATH_PAGE_HOME_DESIGN_COUNTER_BGCOLOR = 'umsocialshare/page/home/design/counter_bgcolor';

    const XML_PATH_PAGE_CMS_DESIGN_WHATSAPP_BGCOLOR = 'umsocialshare/page/cms/design/whatsapp_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_FACEBOOK_BGCOLOR = 'umsocialshare/page/cms/design/facebook_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_TWITTER_BGCOLOR = 'umsocialshare/page/cms/design/twitter_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_LINKEDIN_BGCOLOR = 'umsocialshare/page/cms/design/linkedin_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_GOOGLEPLUS_BGCOLOR = 'umsocialshare/page/cms/design/googleplus_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_PINTEREST_BGCOLOR = 'umsocialshare/page/cms/design/pinterest_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_EMAIL_BGCOLOR = 'umsocialshare/page/cms/design/email_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_COMPACT_BGCOLOR = 'umsocialshare/page/cms/design/compact_bgcolor';
    const XML_PATH_PAGE_CMS_DESIGN_COUNTER_BGCOLOR = 'umsocialshare/page/cms/design/counter_bgcolor';

    const XML_PATH_PAGE_CAT_DESIGN_WHATSAPP_BGCOLOR = 'umsocialshare/page/category/design/whatsapp_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_FACEBOOK_BGCOLOR = 'umsocialshare/page/category/design/facebook_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_TWITTER_BGCOLOR = 'umsocialshare/page/category/design/twitter_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_LINKEDIN_BGCOLOR = 'umsocialshare/page/category/design/linkedin_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_GOOGLEPLUS_BGCOLOR = 'umsocialshare/page/category/design/googleplus_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_PINTEREST_BGCOLOR = 'umsocialshare/page/category/design/pinterest_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_EMAIL_BGCOLOR = 'umsocialshare/page/category/design/email_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_COMPACT_BGCOLOR = 'umsocialshare/page/category/design/compact_bgcolor';
    const XML_PATH_PAGE_CAT_DESIGN_COUNTER_BGCOLOR = 'umsocialshare/page/category/design/counter_bgcolor';

    const XML_PATH_PAGE_PROD_DESIGN_WHATSAPP_BGCOLOR = 'umsocialshare/page/product/design/whatsapp_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_FACEBOOK_BGCOLOR = 'umsocialshare/page/product/design/facebook_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_TWITTER_BGCOLOR = 'umsocialshare/page/product/design/twitter_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_LINKEDIN_BGCOLOR = 'umsocialshare/page/product/design/linkedin_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_GOOGLEPLUS_BGCOLOR = 'umsocialshare/page/product/design/googleplus_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_PINTEREST_BGCOLOR = 'umsocialshare/page/product/design/pinterest_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_EMAIL_BGCOLOR = 'umsocialshare/page/product/design/email_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_COMPACT_BGCOLOR = 'umsocialshare/page/product/design/compact_bgcolor';
    const XML_PATH_PAGE_PROD_DESIGN_COUNTER_BGCOLOR = 'umsocialshare/page/product/design/counter_bgcolor';

    const XML_PATH_PAGE_ADDI_DESIGN_WHATSAPP_BGCOLOR = 'umsocialshare/page/additional/design/whatsapp_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_FACEBOOK_BGCOLOR = 'umsocialshare/page/additional/design/facebook_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_TWITTER_BGCOLOR = 'umsocialshare/page/additional/design/twitter_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_LINKEDIN_BGCOLOR = 'umsocialshare/page/additional/design/linkedin_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_GOOGLEPLUS_BGCOLOR = 'umsocialshare/page/additional/design/googleplus_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_PINTEREST_BGCOLOR = 'umsocialshare/page/additional/design/pinterest_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_EMAIL_BGCOLOR = 'umsocialshare/page/additional/design/email_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_COMPACT_BGCOLOR = 'umsocialshare/page/additional/design/compact_bgcolor';
    const XML_PATH_PAGE_ADDI_DESIGN_COUNTER_BGCOLOR = 'umsocialshare/page/additional/design/counter_bgcolor';

    /**
     * @var RequestHttp
     */
    protected $request;
    
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param Context $context
     * @param RequestHttp $request
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        RequestHttp $request,
        StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
         $this->request = $request;
         $this->storeManager = $storeManager;
    }

    /**
     * Get System Config values
     *
     * @return string|int|array|null
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            ScopeInterface::SCOPE_STORE
        );
    }

   /**
    * Get current url
    *
    * @return string
    */
    public function getCurrentUrl()
    {
        return $this->_urlBuilder->getCurrentUrl();
    }

    /**
     * Is the module enabled in configuration.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Check if current page is home page
     *
     * @return bool
     */
    public function isHomePage()
    {
        $fullActionName = $this->request->getFullActionName();
        
        if ($fullActionName == 'cms_index_index') {
            return true;
        }
    }
    
    /**
     * Check if current page is cms page
     *
     * @return bool
     */
    public function isCmsPage()
    {
        $fullActionName = $this->request->getFullActionName();
        
        if ($fullActionName == 'cms_page_view') {
            return true;
        }
    }

    /**
     * Check if current page is category page
     *
     * @return bool
     */
    public function isCategoryPage()
    {
        
        $fullActionName = $this->request->getFullActionName();
        
        if ($fullActionName == 'catalog_category_view') {
            return true;
        }
    }
    
    /**
     * Check if current page is product page
     *
     * @return bool
     */
    public function isProductPage()
    {
         $fullActionName = $this->request->getFullActionName();
        
        if ($fullActionName == 'catalog_product_view') {
            return true;
        }
    }
    
    /**
     * Get Pub ID
     *
     * @return string
     */
    public function getPubidId()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_GENERAL_PUBID_ID,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get display service
     *
     * @return string
     */
    public function getDisplayServices()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_SERVICES,
            ScopeInterface::SCOPE_STORE
        );
    }
 
    /**
     * Get display whatsapp
     *
     * @return int
     */
    public function getDisplayWhatsapp()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_WHATSAPP,
            ScopeInterface::SCOPE_STORE
        );
    }
   
    /**
     * Get display facebook
     *
     * @return int
     */
    public function getDisplayFacebook()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_FACEBOOK,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get display twitter
     *
     * @return int
     */
    public function getDisplayTwitter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_TWITTER,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get display linkedin
     *
     * @return int
     */
    public function getDisplayLinkedin()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_LINKEDIN,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get display googleplus
     *
     * @return int
     */
    public function getDisplayGoogleplus()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_GOOGLEPLUS,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get display pinterest
     *
     * @return int
     */
    public function getDisplayPinterest()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_PINTEREST,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get display email
     *
     * @return int
     */
    public function getDisplayEmail()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_EMAIL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get display compact
     *
     * @return int
     */
    public function getDisplayCompact()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_COMPACT,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get display counter
     *
     * @return int
     */
    public function getDisplayCounter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SERVICE_DISPLAY_COUNTER,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is Show in homepage
     *
     * @return bool
     */
    public function isShowHomepage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Position in homepage
     *
     * @return string
     */
    public function getPositionInHomepage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Style in homepage
     *
     * @return string
     */
    public function getStyleInHomepage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_STYLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile in homepage
     *
     * @return int
     */
    public function getMobileInHomepage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_MOBILE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile MAX WIDTH in homepage
     *
     * @return int
     */
    public function getMobileMaxWidthInHomepage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_MOBILE_MAXWIDTH,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is Show in cms pages
     *
     * @return bool
     */
    public function isShowCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Position in cms page
     *
     * @return string
     */
    public function getPositionInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Style in cms page
     *
     * @return string
     */
    public function getStyleInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_STYLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile in cms page
     *
     * @return int
     */
    public function getMobileInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_MOBILE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile MAX WIDTH in cms page
     *
     * @return int
     */
    public function getMobileMaxWidthInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_MOBILE_MAXWIDTH,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is Show in Category pages
     *
     * @return bool
     */
    public function isShowCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Position in Category page
     *
     * @return bool
     */
    public function getPositionInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Style in category page
     *
     * @return string
     */
    public function getStyleInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_STYLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile in category page
     *
     * @return int
     */
    public function getMobileInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_MOBILE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile max width in category page
     *
     * @return int
     */
    public function getMobileMaxWidthInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_MOBILE_MAXWIDTH,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is Show in Product pages
     *
     * @return bool
     */
    public function isShowProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Position in Product page
     *
     * @return bool
     */
    public function getPositionInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Style in Product page
     *
     * @return string
     */
    public function getStyleInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_STYLE,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Mobile in Product page
     *
     * @return int
     */
    public function getMobileInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_MOBILE,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Mobile Max width in Product page
     *
     * @return int
     */
    public function getMobileMaxWidthInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_MOBILE_MAXWIDTH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get a list of additionals pages urls for social share
     *
     * @param int $storeId
     * @return bool
     */
    public function getAdditionalPages($storeId = null)
    {
        $additionalPagesString = $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_PAGE_LINKS,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
        $additionalPages = array_filter(
            preg_split('/\r?\n/', $additionalPagesString)
        );
        
        return array_map(
            'trim',
            $additionalPages
        );
    }

    /**
     * Get additionals pages urls
     *
     * @return string
     */
    public function getAdditionalPagesUrls()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_PAGE_LINKS,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Is Show in Additional pages
     *
     * @return bool
     */
    public function isShowAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Position in Additional page
     *
     * @return bool
     */
    public function getPositionInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Style in category page
     *
     * @return string
     */
    public function getStyleInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_STYLE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile in category page
     *
     * @return int
     */
    public function getMobileInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_MOBILE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Mobile max width in category page
     *
     * @return int
     */
    public function getMobileMaxWidthInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_MOBILE_MAXWIDTH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get whatsapp background color in product page
     *
     * @return string
     */
    public function getWhatsappBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_WHATSAPP_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get facebook background color in product page
     *
     * @return string
     */
    public function getFacebookBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_FACEBOOK_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get twitter background color in product page
     *
     * @return string
     */
    public function getTwitterBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_TWITTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get linkedin background color in product page
     *
     * @return string
     */
    public function getLinkedinBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_LINKEDIN_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get googleplus background color in product page
     *
     * @return string
     */
    public function getGoogleplusBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_GOOGLEPLUS_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get pinterest background color in product page
     *
     * @return string
     */
    public function getPinterestBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_PINTEREST_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get email background color in product page
     *
     * @return string
     */
    public function getEmailBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_EMAIL_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get compact background color in product page
     *
     * @return string
     */
    public function getCompactBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_COMPACT_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get counter background color in product page
     *
     * @return string
     */
    public function getCounterBgcolorInProductPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_PROD_DESIGN_COUNTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get whatsapp background color in category page
     *
     * @return string
     */
    public function getWhatsappBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_WHATSAPP_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get facebook background color in category page
     *
     * @return string
     */
    public function getFacebookBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_FACEBOOK_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get twitter background color in category page
     *
     * @return string
     */
    public function getTwitterBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_TWITTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get linkedin background color in category page
     *
     * @return string
     */
    public function getLinkedinBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_LINKEDIN_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get googleplus background color in category page
     *
     * @return string
     */
    public function getGoogleplusBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_GOOGLEPLUS_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get pinterest background color in category page
     *
     * @return string
     */
    public function getPinterestBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_PINTEREST_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get email background color in category page
     *
     * @return string
     */
    public function getEmailBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_EMAIL_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get compact background color in category page
     *
     * @return string
     */
    public function getCompactBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_COMPACT_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get counter background color in category page
     *
     * @return string
     */
    public function getCounterBgcolorInCategoryPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CAT_DESIGN_COUNTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

   /**
    * Get whatsapp background color in cms page
    *
    * @return string
    */
    public function getWhatsappBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_WHATSAPP_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get facebook background color in cms page
     *
     * @return string
     */
    public function getFacebookBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_FACEBOOK_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get twitter background color in cms page
     *
     * @return string
     */
    public function getTwitterBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_TWITTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get linkedin background color in cms page
     *
     * @return string
     */
    public function getLinkedinBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_LINKEDIN_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get googleplus background color in cms page
     *
     * @return string
     */
    public function getGoogleplusBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_GOOGLEPLUS_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get pinterest background color in cms page
     *
     * @return string
     */
    public function getPinterestBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_PINTEREST_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get email background color in cms page
     *
     * @return string
     */
    public function getEmailBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_EMAIL_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get compact background color in cms page
     *
     * @return string
     */
    public function getCompactBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_COMPACT_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get counter background color in cms page
     *
     * @return string
     */
    public function getCounterBgcolorInCmsPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_CMS_DESIGN_COUNTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get whatsapp background color in home page
     *
     * @return string
     */
    public function getWhatsappBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_WHATSAPP_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get facebook background color in home page
     *
     * @return string
     */
    public function getFacebookBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_FACEBOOK_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get twitter background color in home page
     *
     * @return string
     */
    public function getTwitterBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_TWITTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get linkedin background color in home page
     *
     * @return string
     */
    public function getLinkedinBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_LINKEDIN_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get googleplus background color in home page
     *
     * @return string
     */
    public function getGoogleplusBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_GOOGLEPLUS_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get pinterest background color in home page
     *
     * @return string
     */
    public function getPinterestBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_PINTEREST_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get email background color in home page
     *
     * @return string
     */
    public function getEmailBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_EMAIL_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get compact background color in home page
     *
     * @return string
     */
    public function getCompactBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_COMPACT_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get counter background color in home page
     *
     * @return string
     */
    public function getCounterBgcolorInHomePage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_HOME_DESIGN_COUNTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get whatsapp background color in additional page
     *
     * @return string
     */
    public function getWhatsappBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_WHATSAPP_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get facebook background color in additional page
     *
     * @return string
     */
    public function getFacebookBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_FACEBOOK_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get twitter background color in additional page
     *
     * @return string
     */
    public function getTwitterBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_TWITTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get linkedin background color in additional page
     *
     * @return string
     */
    public function getLinkedinBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_LINKEDIN_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
    
    /**
     * Get googleplus background color in additional page
     *
     * @return string
     */
    public function getGoogleplusBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_GOOGLEPLUS_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get pinterest background color in additional page
     *
     * @return string
     */
    public function getPinterestBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_PINTEREST_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get email background color in additional page
     *
     * @return string
     */
    public function getEmailBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_EMAIL_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get compact background color in additional page
     *
     * @return string
     */
    public function getCompactBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_COMPACT_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get counter background color in additional page
     *
     * @return string
     */
    public function getCounterBgcolorInAdditionalPage()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PAGE_ADDI_DESIGN_COUNTER_BGCOLOR,
            ScopeInterface::SCOPE_STORE
        );
    }
}
