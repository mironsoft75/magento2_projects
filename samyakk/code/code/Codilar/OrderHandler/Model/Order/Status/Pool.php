<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\OrderHandler\Model\Order\Status;


class Pool
{
    /**
     * @var string[]
     */
    private $pendingOrderStatuses;

    /**
     * Pool constructor.
     * @param array $pendingOrderStatuses
     */
    public function __construct(
        array $pendingOrderStatuses = []
    )
    {
        foreach ($pendingOrderStatuses as $key => $pendingOrderStatus) {
            if ($pendingOrderStatus instanceof OrderStatusInterface) {
                $pendingOrderStatuses[$key] = $pendingOrderStatus->getOrderStatus();
            }
        }
        $this->pendingOrderStatuses = $pendingOrderStatuses;
    }

    /**
     * @return string[]
     */
    public function getPendingOrderStatuses()
    {
        return $this->pendingOrderStatuses;
    }
}