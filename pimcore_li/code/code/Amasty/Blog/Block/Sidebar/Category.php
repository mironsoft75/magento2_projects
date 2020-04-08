<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;

class Category extends AbstractClass
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::sidebar/categories.phtml");
        $this->_route = 'use_categories';
    }

    public function getBlockHeader()
    {
        return __('Categories');
    }

    public function getCollection()
    {
        if (!$this->_collection){
            $collection = $this->categoryModel->getCollection();
            $collection->addFieldToFilter('status', \Amasty\Blog\Model\Categories::STATUS_ENABLED);
            
            if (!$this->_storeManager->isSingleStoreMode()){
                $collection->addStoreFilter($this->_storeManager->getStore()->getId());
            }

            $collection->setSortOrder('asc');

            $this->_collection = $collection;
        }
        return $this->_collection;
    }

}
