<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:52 PM
 */

namespace Codilar\MasterTables\Model\ResourceModel\LocationName;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'location_id';
    protected $_eventPrefix = 'codilar_location_name_collection';
    protected $_eventObject = 'codilar_location_name_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\LocationName', 'Codilar\MasterTables\Model\ResourceModel\LocationName');
    }
}