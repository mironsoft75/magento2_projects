<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 06/5/19
 * Time: 3:24 PM
 */

namespace Codilar\PriceDecimal\Model;

trait PricePrecisionConfigTrait
{


    /**
     * @return \Codilar\PriceDecimal\Model\ConfigInterface
     */
    public function getConfig()
    {
        return $this->moduleConfig;
    }

    /**
     * @return int|mixed
     */
    public function getPricePrecision()
    {
        if ($this->getConfig()->canShowPriceDecimal()) {
            return $this->getConfig()->getPricePrecision();
        }

        return 0;
    }
}
