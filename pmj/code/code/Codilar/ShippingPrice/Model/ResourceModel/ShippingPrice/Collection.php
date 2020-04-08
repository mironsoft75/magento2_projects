<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/4/19
 * Time: 2:46 PM
 */

namespace Codilar\ShippingPrice\Model\ResourceModel\ShippingPrice;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'pk';
    protected $_eventPrefix = 'shipping_tablerate_collection';
    protected $_eventObject = 'shipping_tablerate_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\ShippingPrice\Model\ShippingPrice', 'Codilar\ShippingPrice\Model\ResourceModel\ShippingPrice');
    }
}
