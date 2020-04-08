<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data\Cart;

interface ItemInterface
{
    /**
     * @return int
     */
    public function getProductId();

    /**
     * @return int
     */
    public function getItemId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getSku();

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @return float
     */
    public function getSpecialPrice();

    /**
     * @return float
     */
    public function getDiscountPercent();

    /**
     * @return string
     */
    public function getImage();

    /**
     * @return \Codilar\Checkout\Api\Data\Cart\Item\OptionInterface[]
     */
    public function getOptions();

    /**
     * @return boolean
     */
    public function getStockStatus();

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return $this
     */
    public function init($item);
}
