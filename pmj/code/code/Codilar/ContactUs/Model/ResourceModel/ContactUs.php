<?php

namespace Codilar\ContactUs\Model\ResourceModel;

/**
 * Class ContactUs
 * @package Codilar\ContactUs\Model\ResourceModel
 */
class ContactUs extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("magento_contact_us", 'id');
    }
}
