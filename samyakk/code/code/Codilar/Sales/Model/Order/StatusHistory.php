<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Model\Order;


use Codilar\Sales\Api\Data\OrderStatusHistoryInterface;
use Magento\Sales\Model\Order\Status\History;

class StatusHistory extends History implements OrderStatusHistoryInterface
{

}