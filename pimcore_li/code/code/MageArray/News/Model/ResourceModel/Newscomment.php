<?php

namespace MageArray\News\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Newscomment
 * @package MageArray\News\Model\ResourceModel
 */
class Newscomment extends AbstractDb
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init('magearray_news_comment', 'comment_id');
    }
}
