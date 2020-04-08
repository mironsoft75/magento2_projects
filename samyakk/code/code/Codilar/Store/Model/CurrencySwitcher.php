<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Model;


use Codilar\Store\Api\Data\CurrencySwitcherInterface;
use Magento\Framework\Model\AbstractModel;
use Codilar\Store\Model\ResourceModel\CurrencySwitcher as ResourceModel;

class CurrencySwitcher extends AbstractModel implements CurrencySwitcherInterface
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getEntityId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId)
    {
        return $this->setData(self::ENTITY_ID, $entityId);
    }
    /**
     * @return int
     */
    public function getQuoteId()
    {
        return $this->getData(self::QUOTE_ID);
    }

    /**
     * @param int $quoteId
     * @return $this
     */
    public function setQuoteId($quoteId)
    {
        return $this->setData(self::QUOTE_ID, $quoteId);
    }

    /**
     * @return string
     */
    public function getQuoteCurrency()
    {
        return $this->getData(self::QUOTE_CURRENCY);
    }

    /**
     * @param string $quoteCurrency
     * @return $this
     */
    public function setQuoteCurrency($quoteCurrency)
    {
        return $this->setData(self::QUOTE_CURRENCY, $quoteCurrency);
    }

    /**
     * @return string
     */
    public function getUpdateCurrencyTo()
    {
        return $this->getData(self::UPDATE_CURRENCY);
    }

    /**
     * @param string $updateCurrency
     * @return $this
     */
    public function setUpdateCurrencyTo($updateCurrency)
    {
        return $this->setData(self::UPDATE_CURRENCY, $updateCurrency);
    }
}