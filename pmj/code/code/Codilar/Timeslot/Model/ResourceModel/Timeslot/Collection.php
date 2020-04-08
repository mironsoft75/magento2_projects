<?php

namespace Codilar\Timeslot\Model\ResourceModel\Timeslot;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Codilar\Timeslot\Model\ResourceModel\Timeslot as ResourceModel;
use Codilar\Timeslot\Model\Timeslot as Model;

class Collection extends AbstractCollection
{
    protected $_idFieldName = "timeslot_id";

    protected function _construct()
    {
        $this->_init(Model::class,ResourceModel::class);
    }
}
