<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Api\Data;


interface CurrencySwitcherInterface
{

    const ENTITY_ID = 'entity_id';
    const QUOTE_ID = 'quote_id';
    const QUOTE_CURRENCY = 'quote_currency';
    const UPDATE_CURRENCY = 'update_currency_to';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * @return int
     */
    public function getQuoteId();

    /**
     * @param int $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId);

    /**
     * @return string
     */
    public function getQuoteCurrency();

    /**
     * @param string $quoteCurrency
     * @return $this
     */
    public function setQuoteCurrency($quoteCurrency);

    /**
     * @return string
     */
    public function getUpdateCurrencyTo();

    /**
     * @param string $updateCurrency
     * @return $this
     */
    public function setUpdateCurrencyTo($updateCurrency);
}