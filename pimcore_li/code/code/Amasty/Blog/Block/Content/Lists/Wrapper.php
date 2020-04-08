<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content\Lists;

class Wrapper extends \Magento\Framework\View\Element\Template
{
    protected function _getPager()
    {
        return $this->getLayout()->getBlock(\Amasty\Blog\Block\Content\Lists::PAGER_BLOCK_NAME);
    }

    public function getNextUrl()
    {
        if ($pager = $this->_getPager()){
            if (!$pager->isLastPage()){
                return $pager->getNextPageUrl();
            }
        }
        return "";
    }

    public function getPreviousUrl()
    {
        if ($pager = $this->_getPager()){
            if (!$pager->isFirstPage()){
                return $pager->getPreviousPageUrl();
            }
        }
        return "";
    }

}