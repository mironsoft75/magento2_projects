<?php
/**
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Model\Order\Item;


use Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface;
use Magento\Framework\DataObject;

class ShipmentTrack extends DataObject implements OrderItemShipmentTrackInterface
{

    /**
     * @return string
     */
    public function getTrackingNumber()
    {
        return $this->getData("tracking_number");
    }

    /**
     * @param string $trackingNumber
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface
     */
    public function setTrackingNumber($trackingNumber)
    {
        return $this->setData("tracking_number", $trackingNumber);
    }

    /**
     * @return string
     */
    public function getTrackingTitle()
    {
        return $this->getData("tracking_title");
    }

    /**
     * @param string $trackingTitle
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface
     */
    public function setTrackingTitle($trackingTitle)
    {
        return $this->setData("tracking_title", $trackingTitle);
    }
}