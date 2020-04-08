<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/11/18
 * Time: 10:52 AM
 */
namespace Codilar\CustomiseJewellery\Model\ResourceModel\CustomiseJewellery;

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
        $this->_init('Codilar\CustomiseJewellery\Model\CustomiseJewellery', 'Codilar\CustomiseJewellery\Model\ResourceModel\CustomiseJewellery');
    }
}

