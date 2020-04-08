<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Model\Data;


use Codilar\Store\Api\Data\CurrencyDataInterface;
use Magento\Framework\DataObject;

class CurrencyData extends DataObject implements CurrencyDataInterface
{

    /**
     * @param \Codilar\Store\Api\Data\CurrencyInterface[] $currencies
     * @return $this
     */
    public function setCurrencies($currencies)
    {
        return $this->setData('currencies', $currencies);
    }

    /**
     * @return \Codilar\Store\Api\Data\CurrencyInterface[]
     */
    public function getCurrencies()
    {
        return $this->getData('currencies');
    }

    /**
     * @param \Codilar\Store\Api\Data\CurrencyInterface $currentCurrency
     * @return $this
     */
    public function setCurrentCurrency($currentCurrency)
    {
        return $this->setData('current_currency', $currentCurrency);
    }

    /**
     * @return \Codilar\Store\Api\Data\CurrencyInterface
     */
    public function getCurrentCurrency()
    {
        return $this->getData('current_currency');
    }
}