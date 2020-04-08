<?php
/**
 *
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Helper;

use Magento\Framework\App\ResourceConnection;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order\Email\Sender\OrderCommentSender;

class Order
{
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var OrderCommentSender
     */
    private $orderCommentSender;
    /**
     * @var OrderManagementInterface
     */
    private $orderManagement;

    /**
     * Order constructor.
     * @param ResourceConnection $resourceConnection
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderManagementInterface $orderManagement
     * @param OrderCommentSender $orderCommentSender
     */
    public function __construct(
        ResourceConnection $resourceConnection,
        OrderRepositoryInterface $orderRepository,
        OrderManagementInterface $orderManagement,
        OrderCommentSender $orderCommentSender
    )
    {
        $this->resourceConnection = $resourceConnection;
        $this->orderRepository = $orderRepository;
        $this->orderCommentSender = $orderCommentSender;
        $this->orderManagement = $orderManagement;
    }

    /**
     * @param \Magento\Sales\Model\Order|\Magento\Sales\Api\Data\OrderInterface $order
     * @param string $status
     * @param string $comment
     * @param bool $notifyCustomer
     * @return \Magento\Sales\Api\Data\OrderInterface|\Magento\Sales\Model\Order
     */
    public function setStatusAndState($order, $status, $comment = "", $notifyCustomer = false) {
        $order->setStatus($status)->setState($this->getStateFromStatus($status));
        $order->addCommentToStatusHistory($comment);
        $this->orderRepository->save($order);
        if ($notifyCustomer) {
            $this->orderCommentSender->send($order, true, $comment);
        }
        return $order;
    }

    /**
     * @param string $status
     * @return string
     */
    public function getStateFromStatus($status) {
        $connection = $this->getConnection();
        try {
            $row = $connection->select()->from($connection->getTableName('sales_order_status_state'))->where('status = ?', $status)->query()->fetch();
            if (!array_key_exists("state", $row)) {
                throw new \Zend_Db_Statement_Exception("Incorrect data");
            }
            return $row['state'];
        } catch (\Zend_Db_Statement_Exception $e) {
            return \Magento\Sales\Model\Order::STATE_NEW;
        }
    }

    /**
     * @param int $orderId
     * @return bool
     */
    public function sendOrderNotification($orderId) {
        return $this->orderManagement->notify($orderId);
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected function getConnection() {
        return $this->resourceConnection->getConnection();
    }
}