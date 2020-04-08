<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 4:38 PM
 */

namespace Pimcore\Aces\Model\ResourceModel\AcesYmm;


use Pimcore\Aces\Model\AcesYmm;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'base_vehicle_id';
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(AcesYmm::class, \Pimcore\Aces\Model\ResourceModel\AcesYmm::class);
    }
}