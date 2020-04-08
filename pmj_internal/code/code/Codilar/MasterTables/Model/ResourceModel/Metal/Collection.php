<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:55 PM
 */

namespace Codilar\MasterTables\Model\ResourceModel\Metal;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'metal_id';
    protected $_eventPrefix = 'codilar_metal_name_collection';
    protected $_eventObject = 'codilar_metal_name_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\Metal', 'Codilar\MasterTables\Model\ResourceModel\Metal');
    }
}