<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Sidebar;

class Search extends AbstractClass
{
    
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::sidebar/search.phtml");
        $this->_route = 'display_search';
    }

    public function getBlockHeader()
    {
        return __("Search the blog");
    }

    public function getSearchUrl()
    {
        return $this->urlHelper->setStoreId($this->getStoreId())->getUrl(null, \Amasty\Blog\Helper\Url::ROUTE_SEARCH);
    }

    public function getQuery()
    {
        return $this->stripTags($this->getRequest()->getParam('query'));
    }
}