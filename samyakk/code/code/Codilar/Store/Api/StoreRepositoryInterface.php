<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Api;

interface StoreRepositoryInterface
{
    /**
     * @return \Codilar\Store\Api\Data\StoreInterface[]
     */
    public function getStores();

    /**
     * @return \Codilar\Store\Api\Data\CurrencyDataInterface
     */
    public function getCurrencies();

    /**
     * @param string $currencyCode
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface
     */
    public function setQuoteCurrency($currencyCode);
}
