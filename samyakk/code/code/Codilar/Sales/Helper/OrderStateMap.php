<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Helper;


class OrderStateMap
{

    CONST ORDER_STATE_PLACED = "Order Placed";
    CONST ORDER_STATE_PENDING = "Pending";
    CONST ORDER_STATE_PROCESSING = "Processing";
    CONST ORDER_STATE_SHIPPED = "Shipped";
    CONST ORDER_STATE_DELIVERED = "Delivered";

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    public function getState($order)
    {
        $status = $order->getStatusLabel();

        if ($order->getState() === $order::STATE_NEW) {
            $status = self::ORDER_STATE_PENDING;
        } else if ($order->getState() === $order::STATE_PROCESSING) {
            $status = self::ORDER_STATE_PROCESSING;
        } else if ($order->getState() === $order::STATE_COMPLETE) {
            $status = self::ORDER_STATE_SHIPPED;
        }

        return $status;
    }

    /**
     * @return string[]
     */
    public function getAllStates()
    {
        return [
            self::ORDER_STATE_PLACED,
            self::ORDER_STATE_PENDING,
            self::ORDER_STATE_PROCESSING,
            self::ORDER_STATE_SHIPPED
        ];
    }
}