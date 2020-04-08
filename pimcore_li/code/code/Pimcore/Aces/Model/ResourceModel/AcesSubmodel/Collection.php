<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 4:38 PM
 */

namespace Pimcore\Aces\Model\ResourceModel\AcesSubmodel;


use Pimcore\Aces\Model\AcesSubmodel;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'id';
    /**
     * Initialize resource collection
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init(AcesSubmodel::class, \Pimcore\Aces\Model\ResourceModel\AcesSubmodel::class);
    }
}