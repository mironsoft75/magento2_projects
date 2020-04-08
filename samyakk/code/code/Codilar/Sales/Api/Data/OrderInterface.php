<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;


interface OrderInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getIncrementId();

    /**
     * @param string $incrementId
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setIncrementId($incrementId);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getState();

    /**
     * @param int $state
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setState($state);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setCreatedAt($createdAt);
    /**
     * @return string
     */
    public function getDeliveryDate();

    /**
     * @param string $deliveryDate
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setDeliveryDate($deliveryDate);

    /**
     * @return string
     */
    public function getGrandTotal();

    /**
     * @param float $grandTotal
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setGrandTotal($grandTotal);

    /**
     * @return string
     */
    public function getShippingAmount();

    /**
     * @param float $shippingAmount
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setShippingAmount($shippingAmount);

    /**
     * @return string
     */
    public function getDiscountAmount();

    /**
     * @param float $discountAmount
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setDiscountAmount($discountAmount);

    /**
     * @return string
     */
    public function getSubtotal();

    /**
     * @param float $subtotal
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setSubtotal($subtotal);

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemInterface[]
     */
    public function getItems();

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemInterface[] $items
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setItems($items);

    /**
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function getShippingAddress();

    /**
     * @param \Magento\Sales\Api\Data\OrderAddressInterface $address
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setShippingAddress(\Magento\Sales\Api\Data\OrderAddressInterface $address = null);

    /**
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function getBillingAddress();

    /**
     * @param \Magento\Sales\Api\Data\OrderAddressInterface $address
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setBillingAddress(\Magento\Sales\Api\Data\OrderAddressInterface $address = null);

    /**
     * @return \Codilar\Sales\Api\Data\OrderStatusHistoryInterface[]
     */
    public function getStatusHistories();

    /**
     * @param bool $asObject
     * @return string
     */
    public function getShippingMethod($asObject = false);

    /**
     * @param string $shippingMethod
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setShippingMethod($shippingMethod);

    /**
     * @return string
     */
    public function getPaymentMethod();

    /**
     * @param string $paymentMethod
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setPaymentMethod($paymentMethod);

    /**
     * @param \Magento\Sales\Api\Data\OrderStatusHistoryInterface[] $statusHistories
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setStatusHistories(array $statusHistories = null);

    /**
     * @return string[]
     */
    public function getOrderStates();

    /**
     * @param string[] $states
     * @return $this
     */
    public function setOrderStates($states);

}