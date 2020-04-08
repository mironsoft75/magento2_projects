<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel\Traits;

trait CollectionTrait
{
    protected function renderFilters()
    {
        if ($this->queryText) {
            $allColumns = $this->getFulltextIndexColumns($this, $this->getMainTable());
            $this->queryText = '%' . $this->queryText . '%';
            foreach ($allColumns[0] as $key => $column) {
                if ($key < 1) {
                    $this->getSelect()
                        ->where($column . ' LIKE ?', $this->queryText);
                    continue;
                }
                $this->getSelect()
                    ->orWhere($column . ' LIKE ?', $this->queryText);
            }
        }
    }

    /**
     * @param $collection
     * @param $indexTable
     * @return array
     */
    protected function getFulltextIndexColumns($collection, $indexTable)
    {
        $indexes = $collection->getConnection()->getIndexList($indexTable);
        $columns = [];
        foreach ($indexes as $index) {
            if (strtoupper($index['INDEX_TYPE']) == 'FULLTEXT') {
                $columns[] = $index['COLUMNS_LIST'];
            }
        }

        return $columns;
    }

    /**
     * @param string $query
     * @return $this
     */
    public function addSearchFilter($query)
    {
        $this->queryText = trim($this->queryText . ' ' . $query);

        return $this;
    }
}
