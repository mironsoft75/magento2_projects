<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content;

class Category extends \Amasty\Blog\Block\Content\Lists implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $_category;

    protected function _construct()
    {
        $this->_isCategory = true;
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        $this->_title = $this->getCategory()->getTitle();
        parent::_prepareLayout();
        $this->getToolbar()->setPagerObject($this->getCategory());
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

            $breadcrumbs->addCrumb($this->getCategory()->getUrlKey(), array(
                'label' => $this->getCategory()->getName(),
                'title' => $this->getCategory()->getName(),
            ));
        }
    }

    public function getPageHeader()
    {
        return $this->getCategory()->getName();
    }

    public function getMetaTitle()
    {
        return $this->getCategory()->getMetaTitle() ?
            $this->settingsHelper->getPrefixTitle($this->getCategory()->getMetaTitle()) : $this->settingsHelper->getPrefixTitle($this->getCategory()->getName());
    }

    public function getDescription()
    {
        return $this->getCategory()->getMetaDescription();
    }

    public function getKeywords()
    {
        return $this->getCategory()->getMetaTags();
    }

    public function getCategory()
    {
        if (!$this->_category){
            $category = $this->categoriesModel->load($this->getRequest()->getParam('id'));
            $this->_category = $category;
        }
        return $this->_category;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Amasty\Blog\Model\Categories::CACHE_TAG . '_' . $this->getCategory()->getId()];
    }
}
