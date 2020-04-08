<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 06/5/19
 * Time: 2:24 PM
 */



namespace Codilar\PriceDecimal\Model;

interface ConfigInterface
{
    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig();

    /**
     * @return mixed
     */
    public function isEnable();
}
