<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 7/11/18
 * Time: 9:05 PM
 */
namespace Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates;

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
        $this->_init('Codilar\StoneAndMetalRates\Model\StoneAndMetalRates', 'Codilar\StoneAndMetalRates\Model\ResourceModel\StoneAndMetalRates');
    }
}


