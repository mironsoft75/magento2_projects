<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/11/18
 * Time: 11:54 AM
 */
namespace Codilar\StoneAndMetalRates\Model\ResourceModel\Activity;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init('Codilar\StoneAndMetalRates\Model\Activity', 'Codilar\StoneAndMetalRates\Model\ResourceModel\Activity');
    }
}
