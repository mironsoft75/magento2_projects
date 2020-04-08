<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;
use Amasty\Blog\Model\AbstractModel;
use Amasty\Blog\Model\Posts;

class Recentpost extends AbstractClass
{
    protected $_collection;

    protected $_cachedIds;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::sidebar/recentpost.phtml");
        $this->_route = 'display_recent';
    }

    protected function _getPostLimit()
    {
        return $this->settingsHelper->getRecentPostsLimit();
    }

    public function getPostsLimit()
    {
        return $this->settingsHelper->getRecentPostsLimit();
    }

    public function getBlockHeader()
    {
        return __('Recent Posts');
    }

    public function getCollection()
    {
        if (!$this->_collection){
            
            $collection = $this->postsModel->getCollection();
            if (!$this->_storeManager->isSingleStoreMode()){
                $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            }
            $collection->addFieldToFilter('status', Posts::STATUS_ENABLED);
            $collection->setUrlKeyIsNotNull();
            $collection->setDateOrder();

            $this->_checkCategory($collection);
            $collection->setPageSize($this->getPostsLimit());

            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    public function showThesis()
    {
        return $this->settingsHelper->getRecentPostsDisplayShort();
    }

    public function showDate()
    {
        return $this->settingsHelper->getRecentPostsDisplayDate();
    }

    public function renderDate($datetime)
    {
        return $this->dateHelper->renderDate($datetime);
    }

    public function hasThumbnail($post)
    {
        $src = $post->getListThumbnail() ? $post->getListThumbnail() : $post->getPostThumbnail();
        return !!$src;
    }

    protected function _getThumbnailSrc($src, $width, $height = null)
    {
        $imageHelper = $this->_helper()->getCommon()->getImage();
        $height = $height ? $height : $width;
        $imageHelper->init($src)->adaptiveResize($width, $height);
        return $imageHelper->__toString();
    }

    public function getThumbnailSrc($post)
    {
        $src = $post->getListThumbnail() ? $post->getListThumbnail() : $post->getPostThumbnail();
        if ($src){
            return $this->resizeHelper->imageResize($src, 60, 60);
        }

        return false;
    }
}