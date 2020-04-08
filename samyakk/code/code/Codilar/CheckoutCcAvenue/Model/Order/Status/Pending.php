<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Model\Order\Status;


use Codilar\CheckoutCcAvenue\Model\Config;
use Codilar\OrderHandler\Model\Order\Status\OrderStatusInterface;

class Pending implements OrderStatusInterface
{
    /**
     * @var Config
     */
    private $config;

    /**
     * Pending constructor.
     * @param Config $config
     */
    public function __construct(
        Config $config
    )
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->config->getPendingOrderStatus();
    }
}