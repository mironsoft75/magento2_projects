<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Codilar\Checkout\Helper\Order as OrderHelper;

class OrderStatusUpdate implements ObserverInterface
{
    const PAYMENT_REFUND = "payment_refund";

    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var OrderHelper
     */
    private $orderHelper;

    /**
     * OrderStatusUpdate constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param OrderHelper $orderHelper
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderHelper $orderHelper
    )
    {
        $this->orderRepository = $orderRepository;
        $this->orderHelper = $orderHelper;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        /* @var $creditmemo \Magento\Sales\Model\Order\Creditmemo */
        $creditmemo = $observer->getEvent()->getCreditmemo();
        $order = $this->orderRepository->get($creditmemo->getOrderId());
        $this->orderHelper->setStatusAndState($order, self::PAYMENT_REFUND, "Payment refunded for this order");
    }
}