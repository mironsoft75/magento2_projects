<?php

namespace MageArray\News\Block\Widget;

use MageArray\News\Block\News;

/**
 * Class Search
 * @package MageArray\News\Block\Widget
 */
class Search extends News
{
    /**
     * @var string
     */
    protected $_searchkey = 'search';

    /**
     * @return mixed
     */
    public function getSearchQuery()
    {
        return $this->getRequest()->getParam('s', '');
    }

    /**
     * @return mixed
     */
    public function getSearchUrl()
    {
        return $this->_dataHelper->getStoreConfig(
            'magearray_news/general/list_url'
        );
    }
}
