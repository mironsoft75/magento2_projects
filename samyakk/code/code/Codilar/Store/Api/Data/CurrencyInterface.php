<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Api\Data;


interface CurrencyInterface
{
    /**
     * @return string
     */
    public function getCurrencySign();

    /**
     * @param string $currencySign
     * @return $this
     */
    public function setCurrencySign($currencySign);

    /**
     * @return string
     */
    public function getCurrencyCode();

    /**
     * @param string $currencyCode
     * @return $this
     */
    public function setCurrencyCode($currencyCode);

    /**
     * @return double
     */
    public function getRate();

    /**
     * @param double $rate
     * @return $this
     */
    public function setRate($rate);
}