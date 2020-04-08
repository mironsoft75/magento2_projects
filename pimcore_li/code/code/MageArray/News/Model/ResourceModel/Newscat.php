<?php

namespace MageArray\News\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Class Newscat
 * @package MageArray\News\Model\ResourceModel
 */
class Newscat extends AbstractDb
{
    /**
     * @var null
     */
    protected $connection = null;
    /**
     * @var
     */
    protected $_resource;

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('magearray_news_category', 'cat_id');
    }

    /**
     * Newscat constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        parent::__construct($context);
    }

    /**
     * @param $urlKey
     * @return string
     */
    public function checkUrlKey($urlKey)
    {
        $select = $this->getLoadByUrlKeySelect($urlKey, 1);

        $select->reset(\Zend_Db_Select::COLUMNS)
            ->columns('cat_id')
            ->limit(1);
        return $this->getConnection()->fetchOne($select);
    }

    /**
     * @param $urlKey
     * @param null $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function getLoadByUrlKeySelect($urlKey, $isActive = null)
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getMainTable())
            ->where(
                'cat_url_key = ?',
                $urlKey
            );
        if (!empty($isActive)) {
            $select->where('cat_status = ?', $isActive);
        }
        return $select;
    }

    /**
     * @return null
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->_resources->getConnection('core_write');
        }
        return $this->connection;
    }
}
