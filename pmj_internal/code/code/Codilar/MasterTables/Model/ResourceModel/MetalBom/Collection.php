<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:59 PM
 */

namespace Codilar\MasterTables\Model\ResourceModel\MetalBom;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'metal_bom_id';
    protected $_eventPrefix = 'codilar_metal_bom_collection';
    protected $_eventObject = 'codilar_metal_bom_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\MetalBom', 'Codilar\MasterTables\Model\ResourceModel\MetalBom');
    }
}