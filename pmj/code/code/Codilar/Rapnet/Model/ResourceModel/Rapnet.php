<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/2/19
 * Time: 2:49 PM
 */

namespace Codilar\Rapnet\Model\ResourceModel;

class Rapnet extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    protected $_idFieldName = 'rapnet_id';
    protected $_date;

    /**
     * Rapnet constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }
    protected function _construct()
    {
        $this->_init('codilar_rapnet', 'rapnet_id');
    }
}
