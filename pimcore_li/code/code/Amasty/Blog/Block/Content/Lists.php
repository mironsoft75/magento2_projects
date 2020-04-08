<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content;

class Lists extends AbstractBlock implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_PREFIX = 'amblog_list_';

    const PAGER_BLOCK_NAME = 'amblog_list_pager';

    protected $_collection;

    protected $_isCategory = false;

    protected $_isTag = false;

    protected $_toolbar = null;

    protected $_cachedIds;

    protected function _prepareCollectionToStart($collection)
    {
        $page = $this->getRequest()->getParam($this->pager->getPageVarName()) ?
                (int)$this->getRequest()->getParam($this->pager->getPageVarName()) :
                1;

        $collection
            ->setPageSize($this->settingsHelper->getPostsLimit())
            ->setCurPage($page)
            ;

        return $this;
    }

    protected function _prepareLayout()
    {
        $this->_title = $this->settingsHelper->getSeoTitle();
        parent::_prepareLayout();

        $this->getToolbar()
            ->setPagerObject($this->listsModel)
            ->setLimit($this->settingsHelper->getPostsLimit())
            ->setCollection($this->getCollection())
            ->setTemplate('Amasty_Blog::list/pager.phtml')
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
            ));
        }
    }

    public function getReadMoreUrl($post)
    {
        return $this->urlHelper->getUrl($post->getId());
    }

    public function getPageHeader()
    {
        return $this->settingsHelper->getBlogMetaTitle();
    }

    protected function _checkTag($collection)
    {
        if (($id = $this->getRequest()->getParam('id')) && $this->getIsTag()){
            $collection->addTagFilter($id);
        }
        return $this;
    }

    protected function _checkCategory($collection)
    {
        if (($id = $this->getRequest()->getParam('id')) && $this->getIsCategory()){
            $collection->addCategoryFilter($id);
        }
        return $this;
    }

    public function getCollection()
    {
        if (!$this->_collection) {
            $posts = $this->objectManagerInterface->create('Amasty\Blog\Model\ResourceModel\Posts\Collection');

            if (!$this->context->getStoreManager()->isSingleStoreMode()){
                $posts->addStoreFilter($this->context->getStoreManager()->getStore()->getId());
            }
            $posts->addFieldToFilter('status', \Amasty\Blog\Model\Posts::STATUS_ENABLED);
            $posts->setUrlKeyIsNotNull();
            $posts->setDateOrder();

            $this->_checkCategory($posts);
            $this->_checkTag($posts);

            $this->_collection = $posts;
        }
        return $this->_collection;
    }

    public function getToolbar()
    {
        if (!$this->_toolbar){
            $toolbar = $this->getLayout()->createBlock('Amasty\Blog\Block\Content\Lists\Pager');
            $this->getLayout()->setBlock(self::PAGER_BLOCK_NAME, $toolbar);
            $this->_toolbar = $toolbar;
        }
        return $this->_toolbar;
    }

    /**
     * Toolbar
     *
     * @return string
     */
    public function getToolbarHtml()
    {
        return $this->getToolbar()->toHtml();
    }

    public function getIsTag()
    {
        return $this->_isTag;
    }

    public function getIsCategory()
    {
        return $this->_isCategory;
    }

    public function getMetaTitle()
    {
        return $this->settingsHelper->getBlogMetaTitle() ? $this->settingsHelper->getBlogMetaTitle() : $this->getTitle();
    }

    public function getKeywords()
    {
        return $this->settingsHelper->getBlogMetaKeywords();
    }

    public function getDescription()
    {
        return $this->settingsHelper->getBlogMetaDescription();
    }

    public function getColorClass()
    {
        return $this->settingsHelper->getIconColorClass();
    }

    public function getRssFeedUrl()
    {

        return $this->getUrl('rss');

        if ($this->_isCategory){
            return $this->getUrl('amblog/rss/category', array(
                'store_id' => $this->storeManagerInterface->getStore()->getId(),
                'id' => $this->getRequest()->getParam('id'),
            ));
        } elseif ($this->_isTag) {
            return $this->getUrl('amblog/rss/tag', array(
                'store_id' => $this->storeManagerInterface->getStore()->getId(),
                'id' => $this->getRequest()->getParam('id'),
            ));
        } else {
            return $this->getUrl('amblog/rss/post', array(
                'store_id' => $this->storeManagerInterface->getStore()->getId(),
            ));
        }
    }

    public function getShowRssLink()
    {
        return $this->settingsHelper->getRssDisplayOnList();
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Amasty\Blog\Model\Lists::CACHE_TAG];
    }
}
