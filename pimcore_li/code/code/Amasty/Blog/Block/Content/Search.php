<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content;

class Search extends \Amasty\Blog\Block\Content\Lists
{
    protected $_search;

    protected function _construct()
    {
        $this->_isSearch = true;
        parent::_construct();
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getToolbar()
            ->setPagerObject($this->getSearch())
            ->setUrlPostfix(sprintf("?query=%s", $this->getRequest()->getParam('query')))
            ;

        return $this;
    }

    protected function _prepareBreadcrumbs()
    {
        parent::_prepareBreadcrumbs();
        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs){
            $breadcrumbs->addCrumb('blog', array(
                'label' => $this->settingsHelper->getBreadcrumb(),
                'title' => $this->settingsHelper->getBreadcrumb(),
                'link' =>  $this->urlHelper->getUrl(),
            ));

            $breadcrumbs->addCrumb($this->getSearch()->getUrlKey(), array(
                'label' => $this->getTitle(),
                'title' => $this->getTitle(),
            ));
        }
    }
/*
    public function getCollection()
    {
        if (!$this->_collection){

            $collection = $this->getSearch()->search();
            $this->_collection = $collection;
            /*
            $collection
                ->addFieldToFilter('status', \Amasty\Blog\Model\Posts::STATUS_ENABLED)
                ->setUrlKeyIsNotNull();

            $this->_collection = $collection;
            */
    /*}
    return $this->_collection;
}
*/
    public function getCollection()
    {
        if (!$this->_collection) {
            $posts = $this->objectManagerInterface->create('Amasty\Blog\Model\ResourceModel\Posts\Collection');

            if (!$this->context->getStoreManager()->isSingleStoreMode()){
                $posts->addStoreFilter($this->context->getStoreManager()->getStore()->getId());
            }
            $posts->addFieldToFilter('status', \Amasty\Blog\Model\Posts::STATUS_ENABLED);
            $posts->loadByQueryText($this->getRequest()->getParam('query'));
            $this->_collection = $posts;
        }
        return $this->_collection;
    }

    protected function _getQueryText()
    {
        return $this->getRequest()->getParam('query');
    }

    public function getTitle()
    {
        return __("Search results for '%1'", $this->escapeHtml($this->_getQueryText()));
    }

    public function getPageHeader()
    {
        return $this->getTitle();
    }

    public function getMetaTitle()
    {
        return $this->settingsHelper->getPrefixTitle($this->getTitle());
    }

    public function getDescription()
    {
        return __("There are following posts founded for the search request '%1'", $this->escapeHtml($this->_getQueryText()));
    }

    public function getKeywords()
    {
        return $this->escapeHtml($this->_getQueryText());
    }


    public function getSearch()
    {
        if (!$this->_search){
            $search = $this->postsModel;
            $this->_search = $search;
        }
        return $this->_search;
    }

    public function getShowRssLink()
    {
        return false;
    }
}