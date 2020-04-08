<?php

namespace Codilar\Rapnet\Model;

/**
 * Class UrlRewrite
 * @package Codilar\Rapnet\Model
 */
class UrlRewrite extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'codilar_rapnet_urlrewrite';
    /**
     * @var string
     */
    protected $_cacheTag = 'codilar_rapnet_urlrewrite';
    /**
     * @var string
     */
    protected $_eventPrefix = 'codilar_rapnet_urlrewrite';

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getUrlRewriteId()];
    }

    protected function _construct()
    {
        $this->_init('Codilar\Rapnet\Model\ResourceModel\UrlRewrite');
    }
}
