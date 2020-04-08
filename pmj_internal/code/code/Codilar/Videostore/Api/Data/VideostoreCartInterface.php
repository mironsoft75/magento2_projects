<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 10:54 AM
 */

namespace Codilar\Videostore\Api\Data;


Interface VideostoreCartInterface
{
    const VIDEOSTORE_CART_ID = 'videostore_cart_id';
    const PRODUCT_ID = 'product_id';
    const VIDEOSTORE_CUSTOMER_ID = 'videostore_customer_id';
    const VIDEOSTORE_CUSTOMER_SESSION_ID = 'videostore_customer_session_id';

    /**
     * @return integer
     */
    public function getVideostoreCartId();

    /**
     * @param $cartId
     * @return $this
     */
    public function setVideostoreCartId($cartId);

    /**
     * @return string
     */
    public function getProductId();

    /**
     * @param $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @return integer
     */
    public function getVideostoreCustomerId();

    /**
     *  @param $videostoreCustomerId
     *  @return $this
     */
    public function setVideostoreCustomerId($videostoreCustomerId);

    /**
     * @return integer
     */
    public function getVideostoreCustomerSessionId();

    /**
     *  @param $videostoreCustomerSessionId
     *  @return $this
     */
    public function setVideostoreCustomerSessionId($videostoreCustomerSessionId);

}