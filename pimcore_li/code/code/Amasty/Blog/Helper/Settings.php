<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Settings extends \Magento\Framework\App\Helper\AbstractHelper
{
    public function generate($title)
    {
        $title = preg_replace('/[«»""!?,.!@£$%^&*{};:()]+/', '', strtolower($title));
        $key = preg_replace('/[^A-Za-z0-9-]+/', '-', $title);
        return $key;
    }

    public function getBlogPostfix()
    {
        return $this->getStoreConfig('amblog/redirect/url_postfix');
    }

    public function getSeoRoute()
    {
        return $this->getStoreConfig('amblog/search_engine/route');
    }

    public function getSeoTitle()
    {
        return $this->getStoreConfig('amblog/search_engine/title');
    }

    public function getFooterLabel()
    {
        return $this->getStoreConfig('amblog/footer_menu/label');
    }
    
    public function getPostsLimit()
    {
        return $this->getStoreConfig('amblog/list/count_per_page'); 
    }

    public function getMenuLabel()
    {
        return $this->getStoreConfig('amblog/top_menu/label');
    }
    
    public function getRedirectToSeoFormattedUrl()
    {
        return $this->getFlag('amblog/redirect/redirect_to_seo_formatted_url');
    }

    public function getIconColorClass()
    {
        return $this->getStoreConfig('amblog/style/color_sheme');
    }

    public function getBlogMetaDescription()
    {
        return $this->getStoreConfig('amblog/search_engine/meta_description');
    }

    public function getBlogMetaTags()
    {
        return $this->getStoreConfig('amblog/search_engine/meta_tags');
    }

    public function getBlogMetaTitle()
    {
        return $this->getStoreConfig('amblog/search_engine/meta_title');
    }
    public function getBlogMetaKeywords()
    {
        return $this->getStoreConfig('amblog/search_engine/meta_keywords');
    }



    public function getMobileList()
    {
        return $this->getStoreConfig('amblog/layout/mobile_list');
    }

    public function getMobilePost()
    {
        return $this->getStoreConfig('amblog/layout/mobile_post');
    }

    public function getDesktopPost()
    {
        return $this->getStoreConfig('amblog/layout/desktop_post');
    }

    public function getDesktopList()
    {
        return $this->getStoreConfig('amblog/layout/desktop_list');
    }

    public function getRssDisplayOnList()
    {
        return $this->getStoreConfig('amblog/rss/display_on_list');
    }

    public function getShowAuthor()
    {
        return $this->getStoreConfig('amblog/post/display_author');
    }

    public function getDisplayViews()
    {
        return $this->getStoreConfig('amblog/post/display_views');
    }

    public function getDateFormat()
    {
        return $this->getStoreConfig('amblog/post/date_manner');
    }

    public function getUseTags()
    {
        return $this->getFlag('amblog/post/display_tags');
    }

    public function getUseCategories()
    {
        return $this->getFlag('amblog/post/display_categories');
    }

    public function getRecentPostsLimit()
    {
        return $this->getStoreConfig('amblog/recent_posts/record_limit');
    }

    public function getRecentPostsDisplayShort()
    {
        return $this->getStoreConfig('amblog/recent_posts/display_short');
    }
    
    public function getRecentPostsDisplayDate()
    {
        return $this->getStoreConfig('amblog/recent_posts/display_date');
    }

    public function getCommentsLimit()
    {
        return $this->getStoreConfig('amblog/comments/record_limit');
    }

    public function getRecentCommentsDisplayShort()
    {
        return $this->getStoreConfig('amblog/comments/display_short');
    }

    public function getRecentCommentsDisplayDate()
    {
        return $this->getStoreConfig('amblog/comments/display_date');
    }

    public function getTagsMinimalPostCount()
    {
        $min = $this->getStoreConfig('amblog/tags/minimal_post_count');
        if (!$min) {
            $min = 0;
        }
        return $min;
    }

    public function getTagsMtEnabled()
    {
        return $this->getStoreConfig('amblog/tags/mt_enabled');
    }

    public function getTagsMtWidth()
    {
        return $this->getStoreConfig('amblog/tags/mt_width');
    }

    public function getTagsMtHeight()
    {
        return $this->getStoreConfig('amblog/tags/mt_height');
    }

    public function getTagsMtBackground()
    {
        return $this->getStoreConfig('amblog/tags/mt_background');
    }

    public function getTagsMtTextcolor()
    {
        return $this->getStoreConfig('amblog/tags/mt_textcolor');
    }

    public function getTagsMtTextcolor2()
    {
        return $this->getStoreConfig('amblog/tags/mt_textcolor2');
    }

    public function  getTagsMtHiColor()
    {
        return $this->getStoreConfig('amblog/tags/mt_hicolor');
    }

    public function getRecentPostsShortLimit()
    {
        return $this->getStoreConfig('amblog/recent_posts/short_limit');
    }

    public function getShowPrintLink()
    {
        return $this->getStoreConfig('amblog/post/display_print');
    }

    public function getPrefixTitle($title)
    {
        if ($prefix = $this->getStoreConfig('amblog/search_engine/title')) {
            $title = $prefix . " - " . $title;
        }
        return $title;
    }

    public function getSocialEnabled()
    {
        return $this->getStoreConfig('amblog/social/enabled');
    }

    public function getCommentsAutoapprove()
    {
        return $this->getStoreConfig('amblog/comments/autoapprove');
    }

    public function getRssComment($storeId = null)
    {
        return $this->getStoreConfig('amblog/rss/comment_feed', $storeId);
    }

    public function getConfPlace($route = null)
    {
        return $this->getStoreConfig("amblog/general/".$route);
    }

    public function getCommentsAllowGuests()
    {
        return $this->getStoreConfig('amblog/comments/allow_guests');
    }

    public function getUseComments()
    {
        return $this->getStoreConfig('amblog/comments/use_comments');
    }

    public function getCommentNotificationsEnabled()
    {
        return $this->getStoreConfig('amblog/notify_customer_comment_replyed/enabled');
    }

    public function getBreadcrumb()
    {
        return $this->getStoreConfig('amblog/search_engine/bread');
    }

    /**
     * @return int
     */
    public function getImageWidth()
    {
        return $this->getStoreConfig('amblog/post/image_width');
    }

    /**
     * @return int
     */
    public function getImageHeight()
    {
        return $this->getStoreConfig('amblog/post/image_height');
    }
    
    private function getStoreConfig($key)
    {
        $configValue = $this->scopeConfig->getValue(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES
        );

        return $configValue;
    }

    private function getFlag($key)
    {
        $configValue = $this->scopeConfig->isSetFlag(
            $key,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORES
        );

        return $configValue;
    }


}