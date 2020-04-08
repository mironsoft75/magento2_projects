<?php

namespace Codilar\ContactUs\Model\ResourceModel\ContactUs;
/**
 * Class Collection
 * @package Codilar\ContactUs\Model\ResourceModel\ContactUs
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Codilar\ContactUs\Model\ContactUs', 'Codilar\ContactUs\Model\ResourceModel\ContactUs');
    }
}
