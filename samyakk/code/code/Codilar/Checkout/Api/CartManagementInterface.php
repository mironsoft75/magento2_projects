<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api;


interface CartManagementInterface
{
    /**
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCart();

    /**
     * @param int $itemId
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeItem($itemId);

    /**
     * @param \Codilar\Checkout\Api\Data\ItemInterface $item
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function addItem($item);

    /**
     * @param string $cartId
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function removeCoupon($cartId);

    /**
     * @param \Codilar\Checkout\Api\Data\MergeCartInterface $mergeData
     * @return \Codilar\Customer\Api\Data\AbstractResponseInterface
     */
    public function mergeCart($mergeData);
}