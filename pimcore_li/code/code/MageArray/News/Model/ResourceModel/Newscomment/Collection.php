<?php

namespace MageArray\News\Model\ResourceModel\Newscomment;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package MageArray\News\Model\ResourceModel\Newscomment
 */
class Collection extends AbstractCollection
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init(
            'MageArray\News\Model\Newscomment',
            'MageArray\News\Model\ResourceModel\Newscomment'
        );
    }
}
