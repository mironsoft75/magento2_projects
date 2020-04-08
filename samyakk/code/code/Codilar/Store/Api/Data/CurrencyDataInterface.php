<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Api\Data;


interface CurrencyDataInterface
{
    /**
     * @param \Codilar\Store\Api\Data\CurrencyInterface $currentCurrency
     * @return $this
     */
    public function setCurrentCurrency($currentCurrency);

    /**
     * @return \Codilar\Store\Api\Data\CurrencyInterface
     */
    public function getCurrentCurrency();
    /**
     * @param \Codilar\Store\Api\Data\CurrencyInterface[] $currencies
     * @return $this
     */
    public function setCurrencies($currencies);

    /**
     * @return \Codilar\Store\Api\Data\CurrencyInterface[]
     */
    public function getCurrencies();


}