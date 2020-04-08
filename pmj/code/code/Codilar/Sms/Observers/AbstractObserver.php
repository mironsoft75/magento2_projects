<?php

namespace Codilar\Sms\Observers;

use Codilar\Api\Helper\Emulator;
use Codilar\Sms\Helper\Transport;
use Magento\Customer\Model\Customer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;

abstract class AbstractObserver implements ObserverInterface{

    protected $transport;
    /**
     * @var Emulator
     */
    private $emulator;
    /**
     * @var Order
     */
    private $order;

    /**
     * AbstractObserver constructor.
     * @param Transport $transport
     * @param Emulator $emulator
     * @param Order $order
     */
    public function __construct(
        Transport $transport,
        Emulator $emulator,
        Order $order
    )
    {
        $this->transport = $transport;
        $this->emulator = $emulator;
        $this->order = $order;
    }

    /**
     * @param Customer $customer
     * @return mixed
     */
    public function getCustomerCountryCode(Customer $customer){
        return $customer->getData('country_code');
    }

    /**
     * @param Order $order
     * @return string
     */
    public function getShippingPhoneNumber(Order $order){
        return $order->getShippingAddress()->getTelephone();
    }

    /**
     * @param string $number
     * @param string $data
     * @return array|bool
     */
    protected function sendSms($number, $data){
        return $this->transport->sendSms($number, $data);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     */
    protected function getTemplate($template, $data = []){
        $this->emulator->startEmulation(Emulator::AREA_FRONTEND, $this->getStoreId());
        $template = $this->transport->getSmsTemplate($template, $data);
        $this->emulator->stopEmulation();
        return $template;
    }

    /**
     * @return int
     */
    abstract protected function getStoreId();

}