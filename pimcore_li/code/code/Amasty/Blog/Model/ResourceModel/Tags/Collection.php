<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel\Tags;

use Amasty\Blog\Model\ResourceModel\Traits\CollectionTrait;

/**
 * Class Collection
 * @package Amasty\Blog\Model\ResourceModel\Tags
 */
class Collection extends \Magento\Rule\Model\ResourceModel\Rule\Collection\AbstractCollection
{
    use CollectionTrait;

    const MIN_SIZE = 1;
    const MAX_SIZE = 10;

    /**
     * @var bool
     */
    protected $_addWheightData = false;
    /**
     * @var bool
     */
    protected $_postDataJoined = false;

    /**
     * @var string
     */
    private $queryText;

    /**
     * @var array
     */
    protected $_map = ['fields' => [
        'tag_id' => 'main_table.tag_id'
    ]];

    public function _construct()
    {
        $this->_init('Amasty\Blog\Model\Tags', 'Amasty\Blog\Model\ResourceModel\Tags');
    }

    /**
     * @return $this
     */
    public function addCount()
    {
        $this->getSelect()
            ->joinLeft(
                ['posttag' => $this->getTable('amasty_blog_posts_tag')],
                'main_table.tag_id = posttag.tag_id',
                ['COUNT(posttag.`tag_id`) as count']
            );
        $this->getSelect()->group('main_table.tag_id');

        return $this;
    }

    /**
     * @param null $store
     * @return $this
     */
    public function addWieghtData($store = null)
    {
        $this->_addWheightData = true;
        $this->_joinPostData();
        $this->getSelect()
            ->columns(['post_count' => new \Zend_Db_Expr("COUNT(post.post_id)")])
            ->group("main_table.tag_id");

        if ($store) {
            if (!is_array($store)) {
                $store = [$store];
            }

            $store = "'".implode("','", $store)."'";
            $postStoreTable = $this->getTable('amasty_blog_posts_store');
            $this->getSelect()
                ->join(['store'=>$postStoreTable], "post.post_id = store.post_id", [])
                ->where(new \Zend_Db_Expr("store.store_id IN ({$store})"));
        }

        return $this;
    }

    /**
     * @return $this
     */
    protected function _joinPostData()
    {
        if ($this->_postDataJoined) {
            return $this;
        }

        $this->_postDataJoined = true;

        $postTagTable = $this->getTable('amasty_blog_posts_tag');
        $this->getSelect()->join(['post'=>$postTagTable], "post.tag_id = main_table.tag_id", []);

        return $this;
    }

    /**
     * @param $count
     * @return $this
     */
    public function setMinimalPostCountFilter($count)
    {
        if ($this->_addWheightData) {
            $this->getSelect()->having("COUNT(post.post_id) >= ?", $count);
        }

        return $this;
    }

    /**
     * @param $status
     * @return $this
     */
    public function setPostStatusFilter($status)
    {
        if (!is_array($status)) {
            $status = [$status];
        }

        $postTable = $this->getTable('amasty_blog_posts');
        $this->getSelect()
            ->join(['postEntity' => $postTable], "post.post_id = postEntity.post_id", [])
            ->where("postEntity.status IN (?)", $status);

        return $this;
    }

    /**
     * @return $this
     */
    public function setNameOrder()
    {
        $this->getSelect()->order("main_table.name ASC");

        return $this;
    }

    /**
     * @param $postIds
     * @return $this
     */
    public function addPostFilter($postIds)
    {
        if (!is_array($postIds)) {
            $postIds =[$postIds];
        }

        $this->_joinPostData();

        $this->getSelect()->where("post.post_id IN (?)", $postIds);

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        $this->renderFilters();
        if ($this->queryText) {
            $this->getSelect()->group('main_table.tag_id');
        }
    }
}
