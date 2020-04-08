<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel\Categories;

class Collection extends \Amasty\Blog\Model\ResourceModel\Abstracts\Collection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'category_id';

    protected $_map = ['fields' => [
        'category_id' => 'main_table.category_id'
    ]];

    public function _construct()
    {
        $this->_init('Amasty\Blog\Model\Categories', 'Amasty\Blog\Model\ResourceModel\Categories');
    }

    public function addStores()
    {

        $this->getSelect()
            ->joinLeft(
                ['store' => $this->getTable('amasty_blog_categories_store')],
                'main_table.category_id = store.category_id',
                []
            )
        ;

        return $this;
    }

    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        parent::load($printQuery, $logQuery);

        //$this->_addStoreData();
        $this->addLinkedData('store', 'store_id', 'store_id');
        
        return $this;
    }

    protected function addLinkedData($linkedTable, $linkedField, $fieldName)
    {
        $connection = $this->getConnection();

        $postId = $this->getColumnValues('category_id');
        $linked = [];
        if (count($postId) > 0) {
            $inCond = $connection->prepareSqlCondition('category_id', ['in' => $postId]);
            $select = $connection->select()->from($this->getTable('amasty_blog_categories_'.$linkedTable))->where($inCond);
            $result = $connection->fetchAll($select);
            foreach ($result as $row) {
                if (!isset($linked[$row['category_id']])) {
                    $linked[$row['category_id']] = [];
                }
                $linked[$row['category_id']][] = $row[$linkedField];
            }
        }

        foreach ($this as $item) {
            if (isset($linked[$item->getId()])) {
                $item->setData($fieldName, $linked[$item->getId()]);
            } else {
                $item->setData($fieldName, []);
            }
        }
    }
    
    public function setSortOrder($direction)
    {
        $this->getSelect()->order("main_table.sort_order {$direction}");
        return $this;
    }

    public function addPostFilter($postId)
    {
        $postTable = $this->getTable('amasty_blog_posts_category');

        $this->getSelect()
            ->join(array('post'=>$postTable), "post.category_id = main_table.category_id", array())
            ->where("post.post_id = ?", $postId)
        ;

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        parent::renderFilters();
        if ($this->queryText) {
            $this->getSelect()
                ->group('main_table.category_id');
        }
    }
}