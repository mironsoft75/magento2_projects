<?php

namespace MageArray\News\Model\ResourceModel\Newscat;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 * @package MageArray\News\Model\ResourceModel\Newscat
 */
class Collection extends AbstractCollection
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_init(
            'MageArray\News\Model\Newscat',
            'MageArray\News\Model\ResourceModel\Newscat'
        );
    }

    /**
     * @param bool $isActive
     * @return $this
     */
    public function setActiveFilter($isActive = true)
    {
        $this->getSelect()->where('main_table.cat_status=?', $isActive);
        return $this;
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreFilter($storeId = 0)
    {
        $connection = $this->getConnection();
        $inCondition = $connection->prepareSqlCondition(
            'main_table.cat_store_id',
            [['finset' => $storeId], ['eq' => 0]]
        );
        $this->getSelect()->where($inCondition);
        return $this;
    }
}
