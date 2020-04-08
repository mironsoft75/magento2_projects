<?php

namespace Codilar\Rapnet\Model\ResourceModel;

/**
 * Class UrlRewrite
 * @package Codilar\Rapnet\Model\ResourceModel
 */
class UrlRewrite extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init("codilar_rapnet_url_rewrite", 'url_rewrite_id');
    }
}
