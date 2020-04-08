<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 21/6/19
 * Time: 5:30 PM
 */

namespace Codilar\PriceDecimal\Helper;


use Magento\Framework\App\Helper\Context;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{


    public function __construct(Context $context)
    {
        parent::__construct($context);
    }


    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getInrEnable()
    {
        return $this->getConfig('catalog_price_inr/general/enable');
    }

}