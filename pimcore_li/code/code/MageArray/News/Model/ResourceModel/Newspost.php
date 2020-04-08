<?php

namespace MageArray\News\Model\ResourceModel;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;
use Magento\Store\Model\Store;

/**
 * Class Newspost
 * @package MageArray\News\Model\ResourceModel
 */
class Newspost extends AbstractDb
{
    /**
     * @var null
     */
    protected $store = null;
    /**
     * @var null
     */
    protected $connection = null;
    /**
     * @var null
     */
    protected $relatedposts = null;
    /**
     * @var null
     */
    protected $relatedproductdata = null;

    /**
     * Newspost constructor.
     * @param Context $context
     * @param null $resourcePrefix
     */
    public function __construct(
        Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }

    /**
     *
     */
    protected function _construct()
    {
        $this->_init('magearray_news_post', 'newspost_id');
    }

    /**
     * @param $urlKey
     * @param $storeId
     * @return string
     */
    public function checkUrlKey($urlKey, $storeId)
    {
        $stores = [Store::DEFAULT_STORE_ID, $storeId];
        $select = $this->getLoadByUrlKeySelect($urlKey, $stores, 1);

        $select->reset(\Zend_Db_Select::COLUMNS)
            ->columns('newspost_id')
            ->order('store_id DESC')
            ->limit(1);
        return $this->getConnection()->fetchOne($select);
    }

    /**
     * @param $urlKey
     * @param $store
     * @param null $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function getLoadByUrlKeySelect($urlKey, $store, $isActive = null)
    {
        $select = $this->getConnection()
            ->select()
            ->from($this->getMainTable())
            ->where(
                'url_key = ?',
                $urlKey
            )->where(
                'store_id IN (?)',
                $store
            );
        if (!empty($isActive)) {
            $select->where('is_active = ?', $isActive);
        }
        return $select;
    }

    /**
     * @return null
     */
    public function getConnection()
    {
        if (!$this->connection) {
            $this->connection = $this->_resources
                ->getConnection('core_write');
        }
        return $this->connection;
    }

    /**
     * @param AbstractModel $object
     * @param mixed $value
     * @param null $field
     * @return $this
     */
    public function load(AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && empty($field)) {
            $field = 'identifier';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * @param AbstractModel $object
     * @return $this
     */
    protected function _afterLoad(AbstractModel $object)
    {
        if ($object->getId()) {
            $relatedPosts = $this->getRelatedPostIds($object->getId());
            $object->setRelatedPostIds($relatedPosts);

            $relatedProducts = $this->getRelatedProductIds($object->getId());
            $object->setRelatedProductIds($relatedProducts);
        }

        return parent::_afterLoad($object);
    }

    /**
     * @param $id
     * @return mixed|null
     */
    public function getRelatedProductid($id)
    {
        if (!isset($this->relatedproductdata)) {
            $relatedProducts = $this->getRelatedProductIds($id);
            $this->relatedproductdata = $relatedProducts;
        }
        return $this->relatedproductdata;
    }

    /**
     * @param $postId
     * @return mixed
     */
    public function getRelatedPostIds($postId)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable('magearray_news_post_relatedpost'),
            'related_id'
        )->where(
            'newspost_id = ?',
            (int)$postId
        );

        return $adapter->fetchCol($select);
    }

    /**
     * @param $postId
     * @return mixed
     */
    public function getRelatedProductIds($postId)
    {
        $adapter = $this->getConnection();

        $select = $adapter->select()->from(
            $this->getTable('magearray_news_post_relatedproduct'),
            'related_id'
        )->where(
            'newspost_id = ?',
            (int)$postId
        );

        return $adapter->fetchCol($select);
    }
}
