<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Data;


use Codilar\Checkout\Api\Data\Cart\ItemInterface;
use Codilar\Checkout\Api\Data\Cart\ItemInterfaceFactory;
use Codilar\Checkout\Api\Data\CartInterface;
use Magento\Framework\Exception\LocalizedException;

class Cart implements CartInterface
{
    /**
     * @var ItemInterfaceFactory
     */
    private $itemFactory;

    /**
     * Cart constructor.
     * @param ItemInterfaceFactory $itemFactory
     */
    public function __construct(
        ItemInterfaceFactory $itemFactory
    )
    {
        $this->itemFactory = $itemFactory;
    }

    /**
     * @var \Magento\Quote\Model\Quote
     */
    private $quote = null;

    /**
     * @return double
     * @throws LocalizedException
     */
    public function getSubtotal()
    {
        return $this->getQuote()->getBaseSubtotal();
    }

    /**
     * @return double
     * @throws LocalizedException
     */
    public function getShippingAmount()
    {
        return $this->getQuote()->getShippingAddress()->getBaseShippingAmount();
    }

    /**
     * @return double
     * @throws LocalizedException
     */
    public function getGrandTotal()
    {
        return $this->getQuote()->getBaseGrandTotal();
    }

    /**
     * @return double
     * @throws LocalizedException
     */
    public function getDiscountAmount()
    {
        $quote = $this->getQuote();
        return $quote->getBaseSubtotal() - $quote->getBaseSubtotalWithDiscount();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getCouponCode()
    {
        $quote = $this->getQuote();
        return (string)$quote->getCouponCode();
    }

    /**
     * @return \Codilar\Checkout\Api\Data\Cart\ItemInterface[]
     * @throws LocalizedException
     */
    public function getItems()
    {
        $items = [];
        foreach ($this->getQuote()->getAllVisibleItems() as $item) {
            /** @var ItemInterface $itemData */
            $itemData = $this->itemFactory->create();
            $items[] = $itemData->init($item);
        }
        return $items;
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getItemCount()
    {
        return $this->getQuote()->getItemsSummaryQty();
    }

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return $this
     */
    public function init($quote)
    {
        $this->quote = $quote;
        return $this;
    }

    /**
     * @return \Magento\Quote\Model\Quote
     * @throws LocalizedException
     */
    protected function getQuote()
    {
        if (!$this->quote instanceof \Magento\Quote\Model\Quote) {
            throw new LocalizedException(__("Cart not initialized"));
        }
        return $this->quote;
    }
}