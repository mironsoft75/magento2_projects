<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */
namespace Codilar\Sales\Model;


use Codilar\Sales\Api\Data\OrderInterface;

class Order extends \Magento\Sales\Model\Order implements OrderInterface
{
    /**
     * @return string
     */
    public function getDeliveryDate()
    {
        return $this->getData("delivery_date");
    }

    /**
     * @param string $deliveryDate
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setDeliveryDate($deliveryDate)
    {
        return $this->setData("delivery_date", $deliveryDate);
    }

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemInterface[]
     */
    public function getItems()
    {
        return $this->getData('items');
    }

    /**
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->getData("payment_method");
    }

    /**
     * @param string $paymentMethod
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setPaymentMethod($paymentMethod)
    {
        return $this->setData("payment_method", $paymentMethod);
    }

    /**
     * @return string
     */
    public function getGrandTotal()
    {
        return $this->formatPriceTxt(parent::getGrandTotal());
    }

    /**
     * @return string
     */
    public function getShippingAmount()
    {
        return $this->formatPriceTxt(parent::getShippingAmount());
    }

    /**
     * @return string
     */
    public function getDiscountAmount()
    {
        return parent::getDiscountAmount() > 0 ? '- ' . $this->formatPriceTxt(parent::getDiscountAmount()) : '';
    }

    /**
     * @return string
     */
    public function getSubtotal()
    {
        return $this->formatPriceTxt(parent::getSubtotal());
    }

    /**
     * @return string[]
     */
    public function getOrderStates()
    {
        return $this->getData('states');
    }

    /**
     * @param string[] $states
     * @return $this
     */
    public function setOrderStates($states)
    {
        return $this->setData('states', $states);
    }
}