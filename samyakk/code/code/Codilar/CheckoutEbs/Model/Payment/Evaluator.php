<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 1:29 PM
 */

namespace Codilar\CheckoutEbs\Model\Payment;

use Codilar\Checkout\Api\Data\PaymentManagement\TypeEvaluatorInterface;
use Codilar\CheckoutEbs\Model\Config;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class Evaluator
 *
 * @package Codilar\CheckoutEbs\Model\Payment
 */
class Evaluator implements TypeEvaluatorInterface
{
    /**
     * Config
     *
     * @var Config
     */
    private $_config;
    /**
     * OrderRepositoryInterface
     *
     * @var OrderRepositoryInterface
     */
    private $_orderRepository;

    /**
     * Evaluator constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     * @param Config $config
     */
    public function __construct(
        OrderRepositoryInterface $orderRepository,
        Config $config
    )
    {
        $this->_config = $config;
        $this->_orderRepository = $orderRepository;
    }

    /**
     * GetStatus
     *
     * @param int $orderId
     * @return boolean
     */
    public function getStatus($orderId)
    {
        try {
            if ($this->getOrder($orderId)->getStatus() == $this->_config->getSuccessOrderStatus()) {
                return true;
            } else {
                return false;
            }
        } catch (NoSuchEntityException $e) {
            return false;
        }

    }

    /**
     * GetMessage
     *
     * @param int $orderId
     * @return string
     */
    public function getMessage($orderId)
    {
        try {
            $orderStatus = $this->getOrder($orderId)->getStatus();
            if ($orderStatus == $this->_config->getSuccessOrderStatus()) {
                return $this->_config->getSuccessOrderMessage();
            } elseif ($orderStatus == $this->_config->getFailureOrderStatus()) {
                return $this->_config->getFailureOrderMessage();
            } elseif ($orderStatus == $this->_config->getPendingOrderStatus()) {
                return "Payment Pending";
            }
        } catch (NoSuchEntityException $e) {
            return "Order Not Found";
        }

    }

    /**
     * Get Oder
     *
     * @param int $orderId
     * @return \Magento\Sales\Api\Data\OrderInterface
     */
    public function getOrder($orderId)
    {
        return $this->_orderRepository->get($orderId);
    }
}