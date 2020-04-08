<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 1:35 PM
 */

namespace Codilar\CheckoutEbs\Model\Order\Status;

use Codilar\CheckoutEbs\Model\Config;
use Codilar\OrderHandler\Model\Order\Status\OrderStatusInterface;

/**
 * Class Pending
 *
 * @package Codilar\CheckoutEbs\Model\Order\Status
 */
class Pending implements OrderStatusInterface
{
    /**
     * Config
     *
     * @var Config
     */
    private $_config;

    /**
     * Pending constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    ) {
        $this->_config = $config;
    }

    /**
     * GetOrderStatus
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->_config->getPendingOrderStatus();
    }
}