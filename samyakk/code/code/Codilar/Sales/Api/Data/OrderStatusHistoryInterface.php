<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;


interface OrderStatusHistoryInterface
{
    /**
     * @return string
     */
    public function getComment();

    /**
     * @param string $comment
     * @return \Codilar\Sales\Api\Data\OrderStatusHistoryInterface
     */
    public function setComment($comment);

    /**
     * @return string
     */
    public function getStatus();

    /**
     * @param string $status
     * @return \Codilar\Sales\Api\Data\OrderStatusHistoryInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return \Codilar\Sales\Api\Data\OrderStatusHistoryInterface
     */
    public function setCreatedAt($createdAt);
}