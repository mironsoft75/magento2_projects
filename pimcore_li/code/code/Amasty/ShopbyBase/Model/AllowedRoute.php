<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBase
 */


namespace Amasty\ShopbyBase\Model;

use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\RequestInterface;

class AllowedRoute
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var mixed
     */
    private $brandCode;

    /**
     * @var  \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->registry = $registry;
        $this->brandCode = $this->scopeConfig
            ->getValue('amshopby_brand/general/attribute_code', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param RequestInterface $request
     * @return bool
     */
    public function isRouteAllowed(RequestInterface $request)
    {
        if ($this->scopeConfig->isSetFlag('amshopby_root/general/enabled', ScopeInterface::SCOPE_STORE)) {
            return true;
        }

        if ($this->brandCode) {
            $seoParams = $this->registry->registry('amasty_shopby_seo_parsed_params');
            $seoBrandPresent = isset($seoParams) && array_key_exists($this->brandCode, $seoParams);
            if ($request->getParam($this->brandCode) || $seoBrandPresent) {
                return true;
            }
        }

        $this->registry->unregister('amasty_shopby_seo_parsed_params');

        return false;
    }
}
