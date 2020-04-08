<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 3:06 PM
 */

namespace Codilar\MasterTables\Model\ResourceModel\StoneBom;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'stone_bom_id';
    protected $_eventPrefix = 'codilar_stone_bom_collection';
    protected $_eventObject = 'codilar_stone_bom_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\StoneBom', 'Codilar\MasterTables\Model\ResourceModel\StoneBom');
    }
}