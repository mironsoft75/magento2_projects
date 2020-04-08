<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/19
 * Time: 2:52 PM
 */

namespace Codilar\Rapnet\Model\ResourceModel\Rapnet;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

    /**
     * @var string
     */
    protected $_idFieldName = 'rapnet_id';
    protected $_eventPrefix = 'codilar_rapnet_collection';
    protected $_eventObject = 'rapnet_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\Rapnet\Model\Rapnet', 'Codilar\Rapnet\Model\ResourceModel\Rapnet');
    }

    /**
     * Truncate RapnetTable
     *
     * @return void
     */
    public function truncateRapnetTable()
    {
        $this->getConnection()->truncateTable($this->getMainTable());
    }
}
