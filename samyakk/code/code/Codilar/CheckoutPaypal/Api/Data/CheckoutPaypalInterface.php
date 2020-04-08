<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Api\Data;


interface CheckoutPaypalInterface
{
    const ENTITY_ID = 'entity_id';
    const ORDER_ID = 'order_id';
    const PAYPAL_PAY_ID = 'paypal_pay_id';
    const PAYPAL_TOKEN = 'paypal_token';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $orderId
     * @return $this
     */
    public function setOrderId($orderId);

    /**
     * @return string
     */
    public function getPaypalPayId();

    /**
     * @param string $paypalPayId
     * @return $this
     */
    public function setPaypalPayId($paypalPayId);

    /**
     * @return string
     */
    public function getPaypalToken();

    /**
     * @param string $paypalToken
     * @return $this
     */
    public function setPaypalToken($paypalToken);
}