<?php

namespace Codilar\Sms\Observers\Sales;

use Codilar\Api\Helper\Emulator;
use Codilar\Sms\Helper\Transport;
use Codilar\Sms\Model\Config;
use Codilar\Sms\Observers\AbstractObserver;
use Magento\Framework\Event\Observer;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Sales\Model\Order as SalesOrder;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session as CustomerSession;

class Order extends AbstractObserver{

    protected $config;
    protected $priceHelper;

    protected $storeId;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var CustomerSession
     */
    private $_customerSession;

    /**
     * Order constructor.
     * @param Transport $transport
     * @param Config $config
     * @param Data $priceHelper
     * @param Emulator $emulator
     * @param StoreManagerInterface $storeManager
     * @param CustomerSession $customerSession
     * @param SalesOrder $order
     */
    public function __construct(
        Transport $transport,
        Config $config,
        Data $priceHelper,
        Emulator $emulator,
        StoreManagerInterface $storeManager,
        CustomerSession $customerSession,
        SalesOrder $order
    )
    {
        $this->config = $config;
        $this->priceHelper = $priceHelper;
        parent::__construct($transport, $emulator,$order);
        $this->storeManager = $storeManager;
        $this->_customerSession = $customerSession;
    }

    public function execute(Observer $observer)
    {
        /* @var SalesOrder $order */
        $order = $observer->getEvent()->getData('order');

        $this->storeId = $order->getStoreId();

        $customerId = $order->getCustomerId();
        /*if(!$customerId) {
            return $this;
        }*/

        $template = null;

        if($order->getData('status') !== $order->getOrigData('status')){
            /* Status was changed */
            $template = $this->getOrderStatusTemplate($order);
        }


        if($template) {
            $customer = ($this->_customerSession->getCustomer())?:$order->getCustomer();
            $customer->getResource()->load($customer, $customerId);
            //$number = ($this->getCustomerPhoneNumber($customer))?:$this->getShippingPhoneNumber($order);
            $number = $this->getShippingPhoneNumber($order);
            return $this->sendSms($number, $template);
        }
        return false;
    }


    /**
     * @param SalesOrder $order
     * @return null|string
     */
    public function getOrderStatusTemplate($order) {
        /**
         * @var $order \Magento\Sales\Model\Order
         */
        $template = null;
        $customerName = ($order->getShippingAddress())?$order->getShippingAddress()->getName():$order->getBillingAddress()->getFirstname()." ".$order->getBillingAddress()->getLastname();

        switch ($order->getStatus()) {
            case "pending":
                if ($this->config->getOrderPlaced()) {
                    $data = [
                        'order_id'          =>  '#'.$order->getIncrementId(),
                        'customer_name'     =>  $customerName,
                        'store_number'      =>  $this->config->getStoreNumber()
                    ];
                    $template = $this->getTemplate('Codilar_Sms::sales/order.html', $data);
                    break;
                } else {
                    return false;
                }
            case "shipped":
                if ($this->config->getOrderDispatched()) {
                    $data = [
                        'order_id'          =>  '#'.$order->getIncrementId(),
                        'customer_name'     =>  $customerName,
                        'store_number'      =>  $this->config->getStoreNumber()
                    ];
                    $template = $this->getTemplate('Codilar_Sms::sales/dispatched.html', $data);
                    break;
                } else {
                    return false;
                }
            case "canceled":
                if ($this->config->getOrderCanceled()) {
                    $data = [
                        'order_id'          =>  '#'.$order->getIncrementId(),
                        'customer_name'     =>  $customerName,
                        'store_number'      =>  $this->config->getStoreNumber()
                    ];
                    $template = $this->getTemplate('Codilar_Sms::sales/canceled.html', $data);
                    break;
                } else {
                    return false;
                }
            case "complete":
                if ($this->config->getOrderDelivered()) {
                    $data = [
                        'order_id'          =>  '#'.$order->getIncrementId(),
                        'customer_name'     =>  $customerName
                    ];
                    $template = $this->getTemplate('Codilar_Sms::sales/delivered.html', $data);
                    break;
                } else {
                    return false;
                }
            case "dispatched":
                if ($this->config->getOrderDispatched()) {
                    $data = [
                        'order_id'          =>  '#'.$order->getIncrementId(),
                        'customer_name'     =>  $customerName

                    ];
                    $template = $this->getTemplate('Codilar_Sms::sales/dispatched.html', $data);
                    break;
                } else {
                    return false;
                }
            case "out_for_delivery":
                if ($this->config->getOrderOutForDelivery()) {
                    $data = [
                        'order_id'          =>  '#'.$order->getIncrementId(),
                        'customer_name'     =>  $customerName
                    ];
                    $template = $this->getTemplate('Codilar_Sms::sales/outfordelivery.html', $data);
                    break;
                } else {
                    return false;
                }

        }
        return $template;
    }

    /**
     * @return int
     */
    protected function getStoreId()
    {
        return $this->storeId;
    }
}