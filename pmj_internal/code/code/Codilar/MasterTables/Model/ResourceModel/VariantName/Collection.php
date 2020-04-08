<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 3:00 PM
 */

namespace Codilar\MasterTables\Model\ResourceModel\VariantName;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'variant_id';
    protected $_eventPrefix = 'codilar_variant_name_collection';
    protected $_eventObject = 'codilar_variant_name_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\MasterTables\Model\VariantName', 'Codilar\MasterTables\Model\ResourceModel\VariantName');
    }
}