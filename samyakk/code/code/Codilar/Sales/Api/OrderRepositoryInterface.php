<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api;


interface OrderRepositoryInterface
{
    /**
     * @return \Codilar\Sales\Api\Data\OrderInterface[]
     */
    public function getOrderHistory();

    /**
     * @param int $orderId
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function getOrder($orderId);

    /**
     * @param string $incrementId
     * @param string $email
     * @return \Codilar\Sales\Api\Data\OrderTrackResponseInterface
     */
    public function getGuestOrderStatus($incrementId, $email);
}