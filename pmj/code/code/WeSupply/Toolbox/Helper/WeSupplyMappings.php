<?php

namespace WeSupply\Toolbox\Helper;

class WeSupplyMappings extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * list of WeSupply order statuses
     */
    const WESUPPLY_ORDER_PROCESSING         = 1;
    const WESUPPLY_ORDER_RECEIVED           = 2;
    const WESUPPLY_ORDER_COMPLETE           = 3;
    const WESUPPLY_ORDER_PENDING_SHIPPING   = 4;
    const WESUPPLY_ORDER_CANCELLED          = 5;
    const WESUPPLY_ORDER_ONHOLD             = 6;
    const WESUPPLY_ORDER_PARTIALLY_COMPLETE = 7;
    const WESUPPLY_ORDER_PAYMENT_FAILURE    = 8;
    const WESUPPLY_ORDER_RETURN             = 9;

    /**
     * @return array
     * maps Magento2 order states with WeSupply order statuses
     */
    public function mapOrderStateToWeSupplyStatus()
    {
        $arrayMaped = array();

        $arrayMaped[\Magento\Sales\Model\Order::STATE_NEW]              = self::WESUPPLY_ORDER_RECEIVED;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_PENDING_PAYMENT]  = self::WESUPPLY_ORDER_ONHOLD;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_PROCESSING]       = self::WESUPPLY_ORDER_PROCESSING;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_COMPLETE]         = self::WESUPPLY_ORDER_COMPLETE;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_CLOSED]           = self::WESUPPLY_ORDER_COMPLETE;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_CANCELED]         = self::WESUPPLY_ORDER_CANCELLED;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_HOLDED]           = self::WESUPPLY_ORDER_ONHOLD;
        $arrayMaped[\Magento\Sales\Model\Order::STATE_PAYMENT_REVIEW]   = self::WESUPPLY_ORDER_PARTIALLY_COMPLETE;

        return $arrayMaped;
    }
}