<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Model\Data;


use Codilar\Store\Api\Data\CurrencyInterface;
use Magento\Framework\DataObject;

class Currency extends DataObject implements CurrencyInterface
{

    /**
     * @return string
     */
    public function getCurrencySign()
    {
        return (string)$this->getData('currency_sign');
    }

    /**
     * @param string $currencySign
     * @return $this
     */
    public function setCurrencySign($currencySign)
    {
        return $this->setData('currency_sign', $currencySign);
    }

    /**
     * @return double
     */
    public function getRate()
    {
        return (double)$this->getData('rate');
    }

    /**
     * @param double $rate
     * @return $this
     */
    public function setRate($rate)
    {
        return $this->setData('rate', $rate);
    }

    /**
     * @return string
     */
    public function getCurrencyCode()
    {
        return (string)$this->getData('currency_code');
    }

    /**
     * @param string $currencyCode
     * @return $this
     */
    public function setCurrencyCode($currencyCode)
    {
        return $this->setData('currency_code', $currencyCode);
    }
}