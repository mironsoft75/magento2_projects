<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model;


use Codilar\Api\Helper\Customer as CustomerHelper;
use Codilar\Checkout\Api\Data\PaymentManagement\TypeEvaluatorInterface;
use Codilar\Checkout\Api\PaymentManagementInterface;
use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface;
use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;

class PaymentManagement implements PaymentManagementInterface
{
    /**
     * @var array
     */
    private $typeEvaluator;
    /**
     * @var AbstractResponseDataInterfaceFactory
     */
    private $abstractResponseDataFactory;
    /**
     * @var CustomerHelper
     */
    private $customerHelper;
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * PaymentManagement constructor.
     * @param AbstractResponseDataInterfaceFactory $abstractResponseDataFactory
     * @param CustomerHelper $customerHelper
     * @param OrderRepositoryInterface $orderRepository
     * @param array $typeEvaluator
     */
    public function __construct(
        AbstractResponseDataInterfaceFactory $abstractResponseDataFactory,
        CustomerHelper $customerHelper,
        OrderRepositoryInterface $orderRepository,
        array $typeEvaluator = []
    )
    {
        $this->abstractResponseDataFactory = $abstractResponseDataFactory;
        $this->customerHelper = $customerHelper;
        $this->typeEvaluator = $typeEvaluator;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param string $type
     * @param int $orderId
     * @return \Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface
     */
    public function getPaymentStatus($type, $orderId)
    {
        /** @var AbstractResponseDataInterface $response */
        $response = $this->abstractResponseDataFactory->create();
        try {
            $customerId = $this->customerHelper->getCustomerIdByToken();
            $order = $this->orderRepository->get($orderId);
            if (!$order->getCustomerId() == $customerId) {
                throw new LocalizedException(__("Illegal Access"));
            }
            $evaluator = $this->getEvaluatorInstanceByType($type);
            $response->setStatus($evaluator->getStatus($orderId))->setMessage($evaluator->getMessage($orderId));
        } catch (NoSuchEntityException $e) {
            $response->setStatus(false)->setMessage(__("Payment method not found"));
        } catch (LocalizedException $localizedException) {
            $response->setStatus(false)->setMessage($localizedException->getMessage());
        }
        return $response;
    }

    /**
     * @param string $type
     * @return TypeEvaluatorInterface
     * @throws NoSuchEntityException
     */
    protected function getEvaluatorInstanceByType($type) {
        if (array_key_exists($type, $this->typeEvaluator)) {
            if ($this->typeEvaluator[$type] instanceof TypeEvaluatorInterface) {
                return $this->typeEvaluator[$type];
            }
        }
        throw NoSuchEntityException::singleField('type', $type);
    }
}