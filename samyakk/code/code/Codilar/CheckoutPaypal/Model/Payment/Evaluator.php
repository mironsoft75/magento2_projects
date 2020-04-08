<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Model\Payment;

use Codilar\Checkout\Api\Data\PaymentManagement\TypeEvaluatorInterface;
use Codilar\CheckoutPaypal\Model\CheckoutPaypalRepository;
use Codilar\CheckoutPaypal\Model\Config;
use Exception;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;

class Evaluator implements TypeEvaluatorInterface
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;
    /**
     * @var CheckoutPaypalRepository
     */
    private $checkoutPaypalRepository;

    /**
     * Evaluator constructor.
     * @param OrderRepositoryInterface $orderRepository
     * @param Config $config
     * @param CheckoutPaypalRepository $checkoutPaypalRepository
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Config $config,
        CheckoutPaypalRepository $checkoutPaypalRepository
    ) {
        $this->config = $config;
        $this->orderRepository = $orderRepository;
        $this->checkoutPaypalRepository = $checkoutPaypalRepository;
    }

    /**
     * @param int $orderId
     * @return boolean
     */
    public function getStatus($orderId)
    {
        try {
            $this->checkoutPaypalRepository->load($orderId, 'order_id');
            if ($this->getOrder($orderId)->getStatus() == $this->config->getSuccessOrderStatus()) {
                return true;
            } else {
                return false;
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @param int $orderId
     * @return string
     */
    public function getMessage($orderId)
    {
        try {
            try {
                $this->checkoutPaypalRepository->load($orderId, 'order_id');
            } catch (NoSuchEntityException $noSuchEntityException) {
                $statusHistoryItem = $this->getOrder($orderId)->getStatusHistoryCollection()->getFirstItem();
                return $statusHistoryItem->getComment();
            }
            $orderStatus = $this->getOrder($orderId)->getStatus();
            if ($orderStatus == $this->config->getSuccessOrderStatus()) {
                return $this->config->getSuccessOrderMessage();
            } elseif ($orderStatus == $this->config->getFailureOrderStatus()) {
                return $this->config->getFailureOrderMessage();
            } elseif ($orderStatus == $this->config->getPendingOrderStatus()) {
                return "Payment Pending";
            }
        } catch (Exception $e) {
            return "Order Not Found";
        }
    }
    /**
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    private function getOrder($orderId)
    {
        return $this->orderRepository->get($orderId);
    }
}
