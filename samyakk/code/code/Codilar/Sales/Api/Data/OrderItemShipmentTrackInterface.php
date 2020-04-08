<?php
/**
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;


interface OrderItemShipmentTrackInterface
{
    /**
     * @return string
     */
    public function getTrackingNumber();

    /**
     * @param string $trackingNumber
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface
     */
    public function setTrackingNumber($trackingNumber);

    /**
     * @return string
     */
    public function getTrackingTitle();

    /**
     * @param string $trackingTitle
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface
     */
    public function setTrackingTitle($trackingTitle);
}