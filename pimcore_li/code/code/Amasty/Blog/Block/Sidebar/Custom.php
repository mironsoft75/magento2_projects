<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;

class Custom extends \Amasty\Blog\Block\Sidebar\Recentpost
{
    protected function _construct()
    {
        if ($transferedData = $this->registry->registry(\Amasty\Blog\Block\Custom::TRANSFER_KEY)){
            foreach ($transferedData as $key => $value){
                $this->setData($key, $value);
            }
        }

        parent::_construct();
    }

    public function showThesis()
    {
        return $this->getData('display_short');
    }

    public function showDate()
    {
        return $this->getData('display_date');
    }

    public function getDisplay()
    {
        return true;
    }

    public function getPostsLimit()
    {
        return $this->getData('record_limit');
    }

    public function getCategoryId()
    {
        if (($categoryId = $this->getData('category_id')) && ($categoryId !== '-')){
            return $categoryId;
        }
        return false;
    }

    public function getBlockHeader()
    {
        if ($this->getCategoryId()){
            $category = $this->categoryModel;
            if (!$this->_storeManager->isSingleStoreMode()){
                $this->categoryModel->setStore($this->_storeManager->getStore()->getId());
            }
            $this->categoryModel->load($this->getCategoryId());
            return $this->escapeHtml($category->getName());
        }
        return parent::getBlockHeader();
    }

    protected function _checkCategory($collection)
    {
        if ($this->getCategoryId()){
            $collection->addCategoryFilter($this->getCategoryId());
        }
        return $this;
    }

}