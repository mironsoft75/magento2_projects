<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel\Posts;

class Collection extends \Amasty\Blog\Model\ResourceModel\Abstracts\Collection
{

    /**
     * @var array
     */
    protected $_map = ['fields' => [
        'post_id' => 'main_table.post_id'
    ]];

    public function _construct()
    {
        $this->_init('Amasty\Blog\Model\Posts', 'Amasty\Blog\Model\ResourceModel\Posts');
    }

    /**
     * @return $this
     */
    public function addStores()
    {
        $this->getSelect()
            ->joinLeft(
                ['store' => $this->getTable('amasty_blog_posts_store')],
                'main_table.post_id = store.post_id',
                []
            );
        return $this;
    }

    /**
     * @param $tagId
     * @return $this
     */
    public function addTagFilter($tagId)
    {
        $this->getSelect()
            ->joinLeft(
                ['tags' => $this->getTable('amasty_blog_posts_tag')],
                'main_table.post_id = tags.post_id',
                []
            )
            ->where('tags.tag_id = ?', $tagId)
            ->group('main_table.post_id');

        return $this;
    }

    /**
     * @param $value
     * @return $this
     */
    public function loadByQueryText($value)
    {
        $this->getSelect()
            ->where('main_table.full_content LIKE ?', "%$value%")
            ->orWhere('main_table.title LIKE ?', "%$value%");

        return $this;
    }

    /**
     * Load data
     *
     * @param boolean $printQuery
     * @param boolean $logQuery
     * @return $this
     */
    public function load($printQuery = false, $logQuery = false)
    {
        if ($this->isLoaded()) {
            return $this;
        }
        parent::load($printQuery, $logQuery);

        $this->addLinkedTables();

        return $this;
    }

    public function addLinkedTables()
    {
        $this->addLinkedData('category', 'category_id', 'categories_ids');
        $this->addLinkedData('store', 'store_id', 'store_id');
        $this->addLinkedData('tag', 'tag_id', 'tag_id');
    }

    /**
     * @param $linkedTable
     * @param $linkedField
     * @param $fieldName
     */
    protected function addLinkedData($linkedTable, $linkedField, $fieldName)
    {
        $connection = $this->getConnection();

        $postId = $this->getColumnValues('post_id');
        $linked = [];
        if (count($postId) > 0) {
            $inCond = $connection->prepareSqlCondition('post_id', ['in' => $postId]);
            $select = $connection->select()->from($this->getTable('amasty_blog_posts_'.$linkedTable))->where($inCond);
            $result = $connection->fetchAll($select);
            foreach ($result as $row) {
                if (!isset($linked[$row['post_id']])) {
                    $linked[$row['post_id']] = [];
                }
                $linked[$row['post_id']][] = $row[$linkedField];
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

    public function setUrlKeyIsNotNull()
    {
        $this->getSelect()->where("main_table.url_key != ''");
    }

    /**
     * @return $this
     */
    public function setDateOrder()
    {
        $this->getSelect()->order("IFNULL(main_table.published_at, main_table.created_at) DESC");

        return $this;
    }

    /**
     * @param $categoryIds
     * @return $this
     */
    public function addCategoryFilter($categoryIds)
    {
        if (!is_array($categoryIds)) {
            # Wrap ids to be array if it not ready before
            $categoryIds = [$categoryIds];
        }

        $categoryTable = $this->getMainTable()."_category";
        $this->getSelect()
            ->join(['categories' => $categoryTable], "categories.post_id = main_table.post_id", [])
            ->where("categories.category_id IN (?)", $categoryIds);

        return $this;
    }

    protected function _renderFiltersBefore()
    {
        parent::renderFilters();
        if ($this->queryText) {
            $this->getSelect()
                ->group('main_table.post_id');
        }
    }
    
}
