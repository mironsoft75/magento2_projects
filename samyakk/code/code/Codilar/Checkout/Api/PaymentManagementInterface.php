<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api;


interface PaymentManagementInterface
{
    /**
     * @param string $type
     * @param int $orderId
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface
     */
    public function getPaymentStatus($type, $orderId);
}