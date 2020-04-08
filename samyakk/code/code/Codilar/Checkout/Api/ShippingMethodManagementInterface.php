<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api;


use Magento\Framework\Exception\NoSuchEntityException;

interface ShippingMethodManagementInterface
{
    /**
     * @param \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface $shippingAddress
     * @return \Codilar\Checkout\Api\Data\ShippingMethodInterface[]
     */
    public function getShippingMethods($shippingAddress);

    /**
     * @param string $shippingMethod
     * @return \Codilar\Checkout\Api\Data\CartInterface
     * @throws NoSuchEntityException
     */
    public function setShippingMethod($shippingMethod);
}