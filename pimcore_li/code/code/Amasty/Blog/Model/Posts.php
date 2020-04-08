<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

class Posts extends AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const STATUS_DISABLED = 0;
    const STATUS_HIDDEN = 1;
    const STATUS_ENABLED = 2;
    const STATUS_SCHEDULED = 3;
    const STATUS_DELETED = 4;
    const STATUS_DRAFT = 5;

    const CUT_LIMITER = '<!-- blogcut -->';
    const CUT_LIMITER_TAG = "<hr class=\"cutter\">";

    const PATTERN_LIMITER_FIND = '/<hr\s+class\s*=\s*\".*?\bcutter\b.*?\".*?>/i';
    const PATTERN_LIMIRER_CUT_AFTER = '/<hr\s+class\s*=\s*\".*?\bcutter\b.*?\".*?>.*/ism';

    const SEARCH_QUERY_KEY = "am_blog_search_query";

    /**
     * cache tag
     */
    const CACHE_TAG = 'amblog_post';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Blog\Model\ResourceModel\Posts');
    }

    public function beforeSave()
    {
        if (!$this->urlHelper->validate($this->getUrlKey())) {
            $this->setUrlKey($this->urlHelper->prepare($this->getUrlKey()));
        }

        return parent::beforeSave();
    }

    public function getTaggedPosts($tagId)
    {
        return $this->getResourceCollection()->addTagFilter($tagId);
    }

    public function getTags()
    {
        if (!$this->hasData('tags')) {
            $tags = $this->_getResource()->getTags($this->getId());
            $this->setData('tags', implode(',', $tags));
        }
        return $this->_getData('tags');
    }

    public function getStores()
    {
        if (!$this->hasData('stores')) {
            $stores = $this->_getResource()->getStores($this->getId());
            $storesArray = [];
            foreach ($stores as $store) {
                $storesArray[] = $store['store_id'];
            }
            $this->setData('stores', $storesArray);
        }

        return $this->_getData('stores');
    }

    public function getCategories()
    {
        if (!$this->hasData('categories')) {
            $cats = $this->_getResource()->getCategories($this->getId());
            $catsArray = [];
            foreach ($cats as $cat) {
                $catsArray[] = $cat['category_id'];
            }
            $this->setData('categories', $catsArray);
        }

        return $this->_getData('categories');
    }

    public function getPostUrl($page = 1)
    {
        return $this
            ->urlHelper
            ->getUrl($this->getId(), \Amasty\Blog\Helper\Url::ROUTE_POST, $page);
    }

    public function getViews()
    {
        return $this->getData('views') + $this->getFlyViews();
    }

    public function getFlyViews()
    {
        $views = $this->objectManagerInterface->create('Amasty\Blog\Model\View')->getCollection();
        $views
            ->addFieldToFilter('post_id', $this->getPostId())
        ;

        return $views->getSize();
    }

    public function getFullContent()
    {
        $content = $this->getData('full_content');

        $content = $this->objectManagerInterface->create('Magento\Cms\Model\Template\FilterProvider')->getPageFilter()->filter($content);
        return $content;
    }

    protected function _getContent($key)
    {
        $content = $this->getData($key);
        $content = $this->objectManagerInterface->create('Magento\Cms\Model\Template\FilterProvider')->getPageFilter()->filter($content);
        $content = str_replace('target="_self"', "", $content);
        return $content;
    }

    public function getShortContent()
    {
        if ($this->getDisplayShortContent()) {
            return $this->_getContent('short_content');
        } else {
            $content = $this->_getContent('full_content');
            $content = str_replace(
                \Amasty\Blog\Model\Posts::CUT_LIMITER,
                \Amasty\Blog\Model\Posts::CUT_LIMITER_TAG,
                $content
            );

            preg_match_all(self::PATTERN_LIMITER_FIND, $content, $matches);

            if (isset($matches[0][0])) {

                $pattern = self::PATTERN_LIMIRER_CUT_AFTER;
                $test = preg_replace($pattern, "", $content);

                return $test;

            } else {
                return $content;
            }
        }
    }

    public function search()
    {
        $query = $this->registry->registry(self::SEARCH_QUERY_KEY);
        if ($query) {
            $this->_getResource()->loadByQueryText($query);
        }
        return $this;
    }

    public function activate()
    {
        $this->setStatus(self::STATUS_ENABLED);
        $this->save();
        return $this;
    }

    public function inactivate()
    {
        $this->setStatus(self::STATUS_DISABLED);
        $this->save();
        return $this;
    }

    public function hasThumbnail()
    {
        return !! $this->getData('post_thumbnail') || !! $this->getData('list_thumbnail');
    }

    public function getPostThumbnailSrc()
    {
        $src = $this->getData('post_thumbnail') ? $this->getData('post_thumbnail') : $this->getData('list_thumbnail');
        if ($src) {
            $imageWidth  = $this->settings->getImageWidth();
            $imageHeight = $this->settings->getImageHeight();

            return $this->resizeHelper->imageResize($src, $imageWidth, $imageHeight);
        }

        return $this->imageHelper->getImageUrl($src);//$this->_thumbnailSrc($src);
    }

    public function getPostImageSrc()
    {
        $src = $this->getData('post_thumbnail') ? $this->getData('post_thumbnail') : $this->getData('list_thumbnail');
        return $this->imageHelper->getImageUrl($src);//$this->_thumbnailSrc($src);
    }

    public function getListThumbnailSrc()
    {
        $src = $this->getData('list_thumbnail') ? $this->getData('list_thumbnail') : $this->getData('post_thumbnail');
        if ($src) {
            $imageWidth  = $this->settings->getImageWidth();
            $imageHeight = $this->settings->getImageHeight();

            return $this->resizeHelper->imageResize($src, $imageWidth, $imageHeight);
        }

        return $this->imageHelper->getImageUrl($src);//$this->_thumbnailSrc($src);
    }

    protected function _thumbnailSrc($src)
    {
        if ($src) {
            $this->imageHelper->init($src);
            return $this->imageHelper->__toString();
        }

        return false;
    }

    public function isScheduled()
    {
        return $this->getStatus() == self::STATUS_SCHEDULED;
    }

    public function activateScheduled()
    {
        if ($this->isScheduled()) {
            $this->getResource()->forceSave();
            $this
                ->setStatus(self::STATUS_ENABLED)
                ->save()
            ;
        }
        return $this;
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [
            \Amasty\Blog\Model\Lists::CACHE_TAG,
            self::CACHE_TAG . '_' . $this->getId()
        ];

        return $identities;
    }
}
