<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Model\ResourceModel\Abstracts;

use Amasty\Blog\Model\ResourceModel\Traits\CollectionTrait;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    use CollectionTrait;

    protected $_mainTable;

    protected $_addStoreData = false;

    protected $_loadStores = false;

    protected $_storeIds;

    /**
     * @var string
     */
    protected $queryText;

    protected function _beforeLoad()
    {
        $this->_applyStoreFilter();
        return parent::_beforeLoad();
    }

    public function addStoreData()
    {
        $this->_loadStores = true;
        return $this;
    }

    public function setOrder($field, $direction = self::SORT_ORDER_DESC)
    {
        if ($field == "stores"){

            $this
                ->_addStoreData()
                ->getSelect()
                ->order("store.store_id {$direction}")
            ;

        } else {
            return parent::setOrder($field, $direction);
        }

        return $this;
    }

    protected function _addStoreData()
    {
        if ($this->_addStoreData){
            return $this;
        }

        $this->_addStoreData = true;
        $table = $this->getMainTable()."_store";
        $idFieldName = $this->getResource()->getIdFieldName();


        $this
            ->getSelect()
            ->joinInner(array('store'=>$table), "store.{$idFieldName} = main_table.{$idFieldName}", array())
            ->group("main_table.{$idFieldName}")
        ;

        return $this;
    }

    protected function _applyStoreFilter($storeIds = null)
    {
        if ($this->_storeIds){

            $this->_addStoreData();

            $store = $this->_storeIds;

            if (!is_array($store)){
                $store = array($store);
            }

            $storesFilter = "'".implode("','", $store)."'";
            $this->getSelect()->where("store.store_id IN ({$storesFilter})");
        }

        return $this;
    }

    public function addStoreFilter($store)
    {
        $this->_storeIds = $store;
        return $this;
    }
}