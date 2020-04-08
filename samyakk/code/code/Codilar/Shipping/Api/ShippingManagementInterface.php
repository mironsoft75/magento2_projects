<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Api;


interface ShippingManagementInterface
{
    /**
     * @param string $zipcode
     * @return \Codilar\Shipping\Api\Data\NotifyResponseInterface
     */
    public function checkDelivery($zipcode);
}