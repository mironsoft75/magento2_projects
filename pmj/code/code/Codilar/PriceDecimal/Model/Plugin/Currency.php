<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 06/5/19
 * Time: 1:40 PM
 */


namespace Codilar\PriceDecimal\Model\Plugin;

class Currency extends PriceFormatPluginAbstract
{

    /**
     * {@inheritdoc}
     *
     * @param \Magento\Framework\CurrencyInterface $subject
     * @param array                                ...$args
     *
     * @return array
     */
    public function beforeToCurrency(
        \Codilar\PriceDecimal\Model\Currency $subject,
        ...$arguments
    ) {
        if ($this->getConfig()->isEnable()) {
            $arguments[1]['precision'] = $subject->getPricePrecision();
        }
        return $arguments;
    }
}
