<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/4/19
 * Time: 2:45 PM
 */

namespace Codilar\ShippingPrice\Model\ResourceModel;


class ShippingPrice  extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'pk';
    protected $_date;

    /**
     * ShippingPrice constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $resourcePrefix
     */
    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context,
                                $resourcePrefix = null)
    {
        parent::__construct($context, $resourcePrefix);
    }
    protected function _construct()
    {
        $this->_init('shipping_tablerate', 'pk');
    }
}