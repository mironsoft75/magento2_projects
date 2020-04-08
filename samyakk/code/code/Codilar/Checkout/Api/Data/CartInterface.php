<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data;


interface CartInterface
{
    /**
     * @return double
     */
    public function getSubtotal();

    /**
     * @return double
     */
    public function getShippingAmount();

    /**
     * @return double
     */
    public function getGrandTotal();

    /**
     * @return double
     */
    public function getDiscountAmount();

    /**
     * @return string
     */
    public function getCouponCode();


    /**
     * @return \Codilar\Checkout\Api\Data\Cart\ItemInterface[]
     */
    public function getItems();

    /**
     * @return int
     */
    public function getItemCount();

    /**
     * @param \Magento\Quote\Model\Quote $quote
     * @return $this
     */
    public function init($quote);

}