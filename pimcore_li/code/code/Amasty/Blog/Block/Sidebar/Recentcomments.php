<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;

class Recentcomments extends AbstractClass
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::sidebar/recentcomments.phtml");
        $this->_route = 'display_recent_comments';
    }

    public function getCommentsLimit()
    {
        return $this->settingsHelper->getCommentsLimit();
    }

    public function getBlockHeader()
    {
        return __('Recent Comments');
    }

    public function getCollection()
    {
        if (!$this->_collection){
            $collection = $this->commentsModel->getCollection();
            if (!$this->_storeManager->isSingleStoreMode()){
                $collection->addPostStoreFilter($this->_storeManager->getStore()->getId());
            }
            $collection
                ->addActiveFilter()
                ->setDateOrder()
                ;

            $collection->setPageSize($this->getCommentsLimit());
            $this->_collection = $collection;
        }
        return $this->_collection;
    }

    public function showThesis()
    {
        return $this->settingsHelper->getRecentCommentsDisplayShort();
    }

    public function showDate()
    {
        return $this->settingsHelper->getRecentCommentsDisplayDate();
    }

    public function renderDate($datetime)
    {
        return $this->dateHelper->renderDate($datetime);
    }
}