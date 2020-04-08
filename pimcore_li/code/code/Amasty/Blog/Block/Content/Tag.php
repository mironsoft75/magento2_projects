<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content;

class Tag extends Lists implements \Magento\Framework\DataObject\IdentityInterface
{
    protected $_tag;

    protected function _construct()
    {
        $this->_isTag = true;
        parent::_construct();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $this->getToolbar()->setPagerObject($this->getTag());
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

            $breadcrumbs->addCrumb($this->getTag()->getUrlKey(), array(
                'label' => $this->getTitle(),
                'title' => $this->getTitle(),
            ));
        }
    }

    public function getTitle()
    {
        return __("Posts tagged '%1'", $this->getTag()->getName());
    }

    public function getPageHeader()
    {
        return $this->getTitle();
    }

    public function getMetaTitle()
    {
        return $this->settingsHelper->getPrefixTitle($this->getTag()->getMetaTitle()) ? $this->getTag()->getMetaTitle() : $this->settingsHelper->getPrefixTitle($this->getTitle());
    }

    public function getKeywords()
    {
        return $this->getTag()->getMetaTags();
    }

    public function getDescription()
    {
        return $this->getTag()->getMetaDescription();
    }

    public function getTag()
    {
        if (!$this->_tag){
            $this->_tag = $this->tagsModel->load($this->getRequest()->getParam('id'));
        }
        return $this->_tag;
    }

    /**
     * Return identifiers for produced content
     *
     * @return array
     */
    public function getIdentities()
    {
        return [\Amasty\Blog\Model\Tags::CACHE_TAG . '_' . $this->getTag()->getId()];
    }
}
