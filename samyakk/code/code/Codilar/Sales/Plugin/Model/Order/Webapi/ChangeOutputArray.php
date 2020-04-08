<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Plugin\Model\Order\Webapi;

use Magento\Sales\Api\Data\OrderItemInterface;
use Magento\Sales\Model\Order\Webapi\ChangeOutputArray as Subject;

class ChangeOutputArray
{
    public function aroundExecute(Subject $subject, callable $proceed, OrderItemInterface $dataObject, array $result)
    {
        if ($dataObject instanceof \Codilar\Sales\Api\Data\OrderItemInterface) {
            return $result;
        } else {
            return $proceed($dataObject, $result);
        }
    }
}