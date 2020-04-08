<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 2:55 PM
 */

namespace Codilar\MasterTables\Model\ResourceModel;


class Metal extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'metal_id';
    protected $_date;

    /**
     * LocationName constructor.
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
        $this->_init('codilar_metal', 'metal_id');
    }
}