<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Model;


use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Api\Helper\Customer;
use Codilar\Core\Helper\Product;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Codilar\Sales\Api\Data\OrderInterface;
use Codilar\Sales\Api\Data\OrderInterfaceFactory;
use Codilar\Sales\Api\Data\OrderItemFormsInterface;
use Codilar\Sales\Api\Data\OrderItemFormsInterfaceFactory;
use Codilar\Sales\Api\Data\OrderItemInterfaceFactory;
use Codilar\Sales\Api\Data\OrderItemInterface;
use Codilar\Sales\Api\OrderRepositoryInterface;
use Codilar\Sales\Helper\Data;
use Codilar\Sales\Helper\OrderStateMap;
use Codilar\Sales\Model\Order\Item;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Sales\Model\ResourceModel\Order as OrderResource;
use Magento\Sales\Model\OrderFactory;
use Codilar\Sales\Api\Data\OrderItemShipmentTrackInterfaceFactory;
use Magento\Shipping\Model\Order\Track;
use Codilar\Sales\Api\Data\OrderTrackResponseInterface as AbstractResponseDataInterface;
use Codilar\Sales\Api\Data\OrderTrackResponseInterfaceFactory as AbstractResponseDataInterfaceFactory;

class OrderRepository extends AbstractApi implements OrderRepositoryInterface
{
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var CollectionFactory
     */
    private $orderCollectionFactory;
    /**
     * @var OrderInterfaceFactory
     */
    private $orderInterfaceFactory;
    /**
     * @var OrderItemInterfaceFactory
     */
    private $orderItemInterfaceFactory;
    /**
     * @var OrderFactory
     */
    private $orderFactory;
    /**
     * @var OrderResource
     */
    private $orderResource;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var Product
     */
    private $productHelper;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var OrderItemFormsInterfaceFactory
     */
    private $orderItemFormsInterfaceFactory;
    /**
     * @var \Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory
     */
    private $formCollectionFactory;
    /**
     * @var \Codilar\DynamicForm\Helper\Response
     */
    private $formResponseHelper;
    /**
     * @var OrderItemShipmentTrackInterfaceFactory
     */
    private $orderItemShipmentTrackInterfaceFactory;
    /**
     * @var AbstractResponseDataInterfaceFactory
     */
    private $abstractResponseDataFactory;
    /**
     * @var OrderStateMap
     */
    private $orderStateMap;

    /**
     * OrderRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param Customer $customerHelper
     * @param CollectionFactory $orderCollectionFactory
     * @param OrderInterfaceFactory $orderInterfaceFactory
     * @param OrderItemInterfaceFactory $orderItemInterfaceFactory
     * @param OrderFactory $orderFactory
     * @param OrderResource $orderResource
     * @param Data $helper
     * @param Product $productHelper
     * @param FormRepositoryInterface $formRepository
     * @param OrderItemFormsInterfaceFactory $orderItemFormsInterfaceFactory
     * @param \Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory
     * @param \Codilar\DynamicForm\Helper\Response $formResponseHelper
     * @param OrderItemShipmentTrackInterfaceFactory $orderItemShipmentTrackInterfaceFactory
     * @param AbstractResponseDataInterfaceFactory $abstractResponseDataFactory
     * @param OrderStateMap $orderStateMap
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        Customer $customerHelper,
        CollectionFactory $orderCollectionFactory,
        OrderInterfaceFactory $orderInterfaceFactory,
        OrderItemInterfaceFactory $orderItemInterfaceFactory,
        OrderFactory $orderFactory,
        OrderResource $orderResource,
        Data $helper,
        Product $productHelper,
        FormRepositoryInterface $formRepository,
        OrderItemFormsInterfaceFactory $orderItemFormsInterfaceFactory,
        \Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory $formCollectionFactory,
        \Codilar\DynamicForm\Helper\Response $formResponseHelper,
        OrderItemShipmentTrackInterfaceFactory $orderItemShipmentTrackInterfaceFactory,
        AbstractResponseDataInterfaceFactory $abstractResponseDataFactory,
        OrderStateMap $orderStateMap
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->customerHelper = $customerHelper;
        $this->orderCollectionFactory = $orderCollectionFactory;
        $this->orderInterfaceFactory = $orderInterfaceFactory;
        $this->orderItemInterfaceFactory = $orderItemInterfaceFactory;
        $this->orderFactory = $orderFactory;
        $this->orderResource = $orderResource;
        $this->helper = $helper;
        $this->productHelper = $productHelper;
        $this->formRepository = $formRepository;
        $this->orderItemFormsInterfaceFactory = $orderItemFormsInterfaceFactory;
        $this->formCollectionFactory = $formCollectionFactory;
        $this->formResponseHelper = $formResponseHelper;
        $this->orderItemShipmentTrackInterfaceFactory = $orderItemShipmentTrackInterfaceFactory;
        $this->abstractResponseDataFactory = $abstractResponseDataFactory;
        $this->orderStateMap = $orderStateMap;
    }


    /**
     * @return OrderInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrderHistory()
    {
        $customerId = $this->customerHelper->getCustomerIdByToken();
        $orderData = [];
        if ($customerId) {
            $orderCollection = $this->getOrderCollection($customerId);
            if ($orderCollection) {
		        $orderCollection->setOrder('entity_id', 'DESC');
                /** @var \Magento\Sales\Model\Order $orderModel */
                foreach ($orderCollection as $orderModel) {
                    /** @var OrderInterface $order */
                    $order = $this->orderInterfaceFactory->create();
                    $order->setId($orderModel->getId())
                        ->setIncrementId($orderModel->getIncrementId())
                        ->setStatus($orderModel->getStatusLabel())
                        ->setState($this->orderStateMap->getState($orderModel))
                        ->setCreatedAt($orderModel->getCreatedAt())
                        ->setDeliveryDate((string)$orderModel->getDeliveryDate())
                        ->setGrandTotal($orderModel->getGrandTotal())
                        ->setShippingAmount($orderModel->getShippingAmount())
                        ->setDiscountAmount(abs($orderModel->getDiscountAmount()))
                        ->setSubtotal($orderModel->getSubtotal())
                        ->setItems($this->getOrderItems($orderModel))
                        ->setStatusHistories($orderModel->getStatusHistories())
                        ->setShippingAddress($orderModel->getShippingAddress())
                        ->setShippingMethod($this->helper->getMethodLabel($orderModel->getShippingMethod()))
                        ->setPaymentMethod($this->helper->getMethodLabel($orderModel->getPayment()->getMethod(), 'payment'))
                        ->setBillingAddress($orderModel->getBillingAddress())
                        ->setOrderStates($this->orderStateMap->getAllStates())
                        ->setOrderCurrencyCode($orderModel->getOrderCurrencyCode());
                    $orderData[]  = $order;
                }
                return $this->sendResponse($orderData);
            }
        }
    }

    /**
     * @param \Magento\Sales\Model\Order $order
     * @return OrderItemInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getOrderItems($order) {
        $items = [];
        foreach ($order->getAllVisibleItems() as $orderItem) {
            /** @var OrderItemInterface $item */
            $item = $this->orderItemInterfaceFactory->create();
            $orderItem->setData("tracking_data", $this->getOrderItemTrackingNumber($orderItem));
            $orderItem->setData("custom_forms", $this->getProductCustomForms($orderItem->getProductId(), $order->getIncrementId()));
            $item->setData($orderItem->getData());
            $items[] = $item;
        }
        return $items;
    }

    /**
     * @param $customerId
     * @return bool|\Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected function getOrderCollection($customerId)
    {
        $collection = $this->orderCollectionFactory->create();
        $collection->addFieldToFilter("customer_id", $customerId);
        $collection->setOrder('entity_id', 'DESC');
        $select = $collection->getSelect();
        $select->joinLeft(
            ['state_map' => $collection->getTable('sales_order_status_state')],
            'main_table.status = state_map.status AND main_table.state = state_map.state',
            ['visible_on_front']
        )->where('state_map.visible_on_front = 1');
        if ($collection->getSize()) {
            return $collection;
        }
        return false;
    }

    /**
     * @param int $orderId
     * @return \Codilar\Sales\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOrder($orderId)
    {
        $orderModel = $this->loadById($orderId);
        $customerId = $this->customerHelper->getCustomerIdByToken();
        if (!$customerId || !$orderModel->getId() || $orderModel->getCustomerId() != $customerId) {
            throw NoSuchEntityException::singleField("Order ID", $orderId);
        }
        /** @var OrderInterface $order */
        $order = $this->orderInterfaceFactory->create();
        $orderModel->getPayment()->getMethod();
        $order->setId($orderModel->getId())
            ->setIncrementId($orderModel->getIncrementId())
            ->setStatus($orderModel->getStatus())
            ->setState($this->orderStateMap->getState($orderModel))
            ->setCreatedAt($orderModel->getCreatedAt())
            ->setDeliveryDate((string)$orderModel->getDeliveryDate())
            ->setGrandTotal($orderModel->getGrandTotal())
            ->setShippingAmount($orderModel->getShippingAmount())
            ->setDiscountAmount($orderModel->getDiscountAmount())
            ->setSubtotal($orderModel->getSubtotal())
            ->setItems($this->getOrderItems($orderModel))
            ->setStatusHistories($orderModel->getStatusHistories())
            ->setShippingAddress($orderModel->getShippingAddress())
            ->setShippingMethod($this->helper->getMethodLabel($orderModel->getShippingMethod()))
            ->setPaymentMethod($this->helper->getMethodLabel($orderModel->getPayment()->getMethod(), 'payment'))
            ->setBillingAddress($orderModel->getBillingAddress())
            ->setOrderStates($this->orderStateMap->getAllStates())
            ->setOrderCurrencyCode($orderModel->getOrderCurrencyCode());
        return $this->sendResponse($order);
    }

    /**
     * @param int $orderId
     * @return \Magento\Sales\Model\Order
     */
    protected function loadById($orderId)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderFactory->create();
        $this->orderResource->load($order, $orderId);
        return $order;
    }

    /**
     * @param int $productId
     * @param string $incrementId
     * @return OrderItemFormsInterface []
     */
    protected function getProductCustomForms($productId, $incrementId)
    {
        $product = $this->productHelper->getProduct($productId);
        $customForms = $product->getData("custom_forms");
        $data = [];
        if ($customForms) {
            $customForms = explode(",", $customForms);
            if (is_array($customForms)) {
                $data = [];
                foreach ($customForms as $customForm) {
                    try {
                        $form = $this->formRepository->load($customForm);
                    } catch (NoSuchEntityException $e) {
                        $form = false;
                    }
                    if ($form) {
                        if (!$this->getIsFormFilled($form->getId(), $incrementId)) {
                            /** @var OrderItemFormsInterface $orderItemForm */
                            $orderItemForm = $this->orderItemFormsInterfaceFactory->create();
                            $orderItemForm->setIdentifier($form->getIdentifier())
                                ->setLabel($form->getTitle());
                            $data[] = $orderItemForm;
                        }
                    }

                }
                return $data;
            }
        }
        return $data;
    }

    /**
     * @param $formId
     * @param $incrementId
     * @return bool
     */
    protected function getIsFormFilled($formId, $incrementId)
    {
        try {
            $this->formResponseHelper->getFilledResponse($formId, $incrementId);
            return true;
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * @param Item $orderItem
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface|array
     */
    protected function getOrderItemTrackingNumber($orderItem)
    {
        /** @var \Magento\Sales\Model\ResourceModel\Order\Item\Collection $collection */
        $collection = $orderItem->getCollection();
        $collection->addFieldToFilter("main_table.item_id", $orderItem->getItemId());
        $collection->getSelect()->join(["ssi" => "sales_shipment_item"],"main_table.item_id = ssi.order_item_id AND main_table.sku = ssi.sku")
                                ->join(["sst" => "sales_shipment_track"],"ssi.parent_id = sst.parent_id", ["item_id" => "main_table.item_id", "tracking_number" => "track_number", "tracking_title" => "title"]);
        if ($collection->getSize()) {
            $track = $collection->getFirstItem();
            /** @var \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface $shipmentTrack */
            $shipmentTrack = $this->orderItemShipmentTrackInterfaceFactory->create();
            $shipmentTrack->setTrackingNumber($track->getTrackingNumber())
                          ->setTrackingTitle($track->getTrackingTitle());
            return $shipmentTrack;
        }
        return [];
    }

    /**
     * @param string $incrementId
     * @param string $email
     * @return \Codilar\Sales\Api\Data\OrderTrackResponseInterface
     */
    public function getGuestOrderStatus($incrementId, $email)
    {
        try {
            $order = $this->orderFactory->create();
            $this->orderResource->load($order, $incrementId, 'increment_id');
            if (!$order->getId()) {
                throw NoSuchEntityException::singleField('increment_id', $incrementId);
            }
            if ($order->getCustomerEmail() !== $email) {
                throw NoSuchEntityException::doubleField('increment_id', $incrementId, 'customer_email', $email);
            }
            $data = [
                'status'    =>  true,
                'message'   =>  $this->orderStateMap->getState($order),
                'states'    =>  $this->orderStateMap->getAllStates()
            ];
        } catch (LocalizedException $localizedException) {
            $data = [
                'status'    =>  false,
                'message'   =>  __("Order not found. Please check if the entered details are correct and try again"),
                'states'    =>  []
            ];
        } catch (\Exception $exception) {
            $data = [
                'status'    =>  false,
                'message'   =>  __("Could not retrieve order status"),
                'states'    =>  []
            ];
        }
        /** @var AbstractResponseDataInterface $response */
        $response = $this->abstractResponseDataFactory->create();
        $response->setStatus($data['status'])->setMessage($data['message'])->setAllStates($data['states']);
        return $this->sendResponse($response);
    }
}
