<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 06/5/19
 * Time: 1:24 PM
 */


namespace Codilar\PriceDecimal\Model\Plugin\Local;

use Codilar\PriceDecimal\Model\Plugin\PriceFormatPluginAbstract;

class Format extends PriceFormatPluginAbstract
{

    /**
     * {@inheritdoc}
     *
     * @param $subject
     * @param $result
     *
     * @return mixed
     */
    public function afterGetPriceFormat($subject, $result)
    {
        $precision = $this->getPricePrecision();

        if ($this->getConfig()->isEnable()) {
            $result['precision'] = $precision;
            $result['requiredPrecision'] = $precision;
        }

        return $result;
    }
}
