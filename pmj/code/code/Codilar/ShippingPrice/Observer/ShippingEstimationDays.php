<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 10:21 AM
 */

namespace Codilar\ShippingPrice\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Customer\Model\Session;
use Codilar\ShippingPrice\Helper\Data;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Psr\Log\LoggerInterface;

class ShippingEstimationDays implements ObserverInterface
{
    const VISIBLITY = "4";
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;
    /**
     * @var Session
     */
    protected $customerSession;
    /**
     * @var Data
     */
    protected $shippingPriceHelper;
    /**
     * @var CollectionFactory
     */
    protected $orderCollectionFactory;


    /**
     * ShippingEstimationDays constructor.
     * @param OrderRepositoryInterface $OrderRepositoryInterface
     * @param ProductRepositoryInterface $productRepository
     * @param Session $customerSession
     * @param Data $shippingPriceHelper
     * @param CollectionFactory $orderCollectionFactory
     */
    public function __construct
    (
        OrderRepositoryInterface $OrderRepositoryInterface,
        ProductRepositoryInterface $productRepository,
        Session $customerSession,
        Data $shippingPriceHelper,
        CollectionFactory $orderCollectionFactory
    )
    {
        $this->orderRepository = $OrderRepositoryInterface;
        $this->productRepository = $productRepository;
        $this->customerSession = $customerSession;
        $this->shippingPriceHelper = $shippingPriceHelper;
        $this->orderCollectionFactory = $orderCollectionFactory;

    }

    /**
     * @return null|string
     */
    public function getCustomerDeliveryPostcode()
    {
        $customer = $this->customerSession->getCustomer();
        if ($customer) {
            $shippingAddress = $customer->getDefaultShippingAddress();
            if ($shippingAddress) {
                return $shippingAddress->getPostcode();
            }
        }
        return null;
    }

    /**
     * @param $productId
     * @param $pin
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getEstimationDays($productId, $pin)
    {
        $product = $this->productRepository->getById($productId);
        if ($product->getVisibility() == self::VISIBLITY) {
            $manufacturingDays = $product->getManufacturingDays();
            if ($pin) {
                $deliverDays = $this->shippingPriceHelper->getDays($pin);
                return (int)$manufacturingDays + (int)$deliverDays;
            }
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {

        try {
            $order = $observer->getOrder();
            $shippingPostCode = $order->getShippingAddress()->getData("postcode");
            foreach ($order->getAllItems() as $orderItem) {
                $totaldays = $this->getEstimationDays($orderItem->getProductId(), $shippingPostCode);
                $product = $this->productRepository->getById($orderItem->getProductId());
                $orderItem->setEstimatedDays($totaldays);
                if ($product->getVisibility() == self::VISIBLITY) {
                    /**
                     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $sales
                     */
                    $sales = $this->orderCollectionFactory->create()->addFieldToFilter('product_id', $orderItem->getProductId());
                }

            }
        } catch (\Exception $e) {

        }
    }
}