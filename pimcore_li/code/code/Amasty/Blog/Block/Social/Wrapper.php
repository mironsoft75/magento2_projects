<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Social;

class Wrapper extends \Amasty\Blog\Block\Content\Post
{

    const MAX_IMAGE_WIDTH = 1000;
    const MAX_IMAGE_HEIGHT = 1000;

    protected $_tags;

    protected $_collection;
    
    public function isGooglePlusEnabled()
    {
        return (in_array('googleplus', $this->_helper()->getSocialNetworks()));
    }

    public function isFacebookPlusEnabled()
    {
        return (in_array('facebook', $this->_helper()->getSocialNetworks()));
    }

    public function getPostTitle()
    {
        return htmlspecialchars($this->getPost()->getTitle());
    }

    public function getPostMetaDescription()
    {
        $text = htmlspecialchars(str_replace("\n", "", $this->getPost()->getMetaDescription()));
        $text = $this->_helper()->getCommon()->getStrings()->strLimit($text, 200, false);
        return $text;
    }

    public function getPostUrl()
    {
        return $this->getPost()->getPostUrl();
    }

    public function hasThumbnail()
    {
        return !!$this->getPost()->getPostThumbnail() || !!$this->getPost()->getListThumbnail();
    }

    public function getThumbnailSrc()
    {
        $imageHelper = $this->_helper()->getCommon()->getImage();

        $src =  $this->getPost()->getPostThumbnail() ? $this->getPost()->getPostThumbnail() : $this->getPost()->getListThumbnail();
        if ($src){
            $imageHelper->init( str_replace("/", DS, $src) );
            return $imageHelper->setMaxSize(self::MAX_IMAGE_WIDTH, self::MAX_IMAGE_HEIGHT)->keepFrame(false);
        }
    }

    public function getPublisherFacebook()
    {
        return $this->_helper()->getPublisherFacebook();
    }

    public function getAuthorFacebook()
    {
        return $this->getPost()->getFacebookProfile();
    }

    public function getPublisherTwitter()
    {
        return $this->_helper()->getPublisherTwitter();
    }

    public function getAuthorTwitter()
    {
        return $this->getPost()->getTwitterProfile();
    }

    public function getPostCategories()
    {
        $categories = $this->getPost()->getCategories();
        if (count($categories)){
            foreach ($categories as $categoryId){
                return $this->_helper()->getCategoryHelper()->getCategoryName($categoryId);
            }
        }
    }

    public function getPostTags()
    {
        if (!$this->_tags){
            $collection = $this->tagsModel->getCollection();
            
            $store = $this->_storeManager->isSingleStoreMode() ? null : $this->_storeManager->getStore()->getId();
            $collection
                ->addPostFilter(
                    $this->getPost()->getId()
                )
                ->addWieghtData(
                    $store
                )
                ->setMinimalPostCountFilter(
                    $this->settingsHelper->getTagsMinimalPostCount()
                )
                ->setPostStatusFilter(
                    \Amasty\Blog\Model\Posts::STATUS_ENABLED
                )
                ->setNameOrder()
            ;

            $this->_tags = $collection;
        }
        return $this->_tags;
    }

    protected function _prepareISO86O1Time($datetime)
    {
        //return str_replace(" ", "T", $datetime).$this->dateHe->getTimeZoneOffset(true);
    }

    public function getPostPublishedDate()
    {
        return $this->_prepareISO86O1Time($this->getPost()->getPublishedAt());
    }

    public function getPostModifiedDate()
    {
        return $this->_prepareISO86O1Time($this->getPost()->getUpdatedAt());
    }
}