<?php

namespace Codilar\Rapnet\Model\ResourceModel\UrlRewrite;

/**
 * Class Collection
 * @package Codilar\Rapnet\Model\ResourceModel\UrlRewrite
 */
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected function _construct()
    {
        $this->_init('Codilar\Rapnet\Model\UrlRewrite', 'Codilar\Rapnet\Model\ResourceModel\UrlRewrite');
    }
}
