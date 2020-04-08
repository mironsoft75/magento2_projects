<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;

class Tag extends AbstractClass
{
    protected $_collection;

    protected $_sizes = array(
        1 => '11px',
        2 => '11px',
        3 => '14ox',
        4 => '16px',
        5 => '19px',
        6 => '22px',
        7 => '26px',
        8 => '28px',
        9 => '32px',
        10 => '34px',
    );

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::sidebar/tags.phtml");
        $this->_route = 'use_tags';
    }
    
    public function getCollection()
    {
        if (!$this->_collection){
            $collection = $this->tagsModel->getCollection();
            $store = $this->_storeManager->isSingleStoreMode() ? null : $this->_storeManager->getStore()->getId();
            $collection
                ->addWieghtData($store)
                ->setMinimalPostCountFilter($this->settingsHelper->getTagsMinimalPostCount())
                ->setPostStatusFilter(\Amasty\Blog\Model\Posts::STATUS_ENABLED)
                ->setNameOrder()
                ;

            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    public function getMtEnabled()
    {
        return $this->settingsHelper->getTagsMtEnabled();
    }

    public function getMtWidth()
    {
        return $this->settingsHelper->getTagsMtWidth();
    }

    public function getMtHeight()
    {
        return $this->settingsHelper->getTagsMtHeight();
    }

    public function getMtBackground()
    {
        return $this->settingsHelper->getTagsMtBackground();
    }

    public function getMtTextColor()
    {
        return $this->settingsHelper->getTagsMtTextcolor();
    }

    public function getMtTextColor2()
    {
        return $this->settingsHelper->getTagsMtTextcolor2();
    }

    public function getMtHiColor()
    {
        return $this->settingsHelper->getTagsMtHiColor();
    }

    protected function _getTagSize($tagType)
    {
        return $this->_sizes[$tagType];
    }

    public function getTagSizeClass($tag)
    {
        return round($tag->getTagType());
    }

    public function getMtTagsHtml()
    {
        $tags = "";
        foreach ($this->getCollection() as $tag){
            $url = $tag->getTagUrl();
            $size = $this->_getTagSize($tag->getTagType());
            $name = $tag->getName();
            $title = __("%1 Topics", $tag->getPostCount());
            $tags .= "<a href='{$url}' style='font-size: {$size};' title='{$title}'>{$name}</a>";

        }
        return urlencode("<tags>".$tags."</tags>");
    }

    public function isActive(\Amasty\Blog\Model\Tags $tag)
    {
        if (
            ($this->getRequest()->getModuleName() == "amblog") &&
            ($this->getRequest()->getControllerName() == "index") &&
            ($this->getRequest()->getActionName() == "tag") &&
            ($this->getRequest()->getParam('id') == $tag->getTagId())
        ){
            return true;
        } else {
            return false;
        }
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        if ($this->wantAsserts()){

            if ($this->settingsHelper->getTagsMtEnabled()){
                $this->getLayout()->createBlock(
                    'Magento\Framework\View\Element\Template',
                    '',
                    ['data' => ['template' => 'Amasty_Blog::sidebar/tags/js.phtml']]
                );
            }
        }

        return $this;
    }
}