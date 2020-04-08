<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 19/9/19
 * Time: 2:46 PM
 */

namespace Codilar\CheckoutEbs\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Codilar\CheckoutEbs\Model\Ebs;

/**
 * Class BeforeOrderPlaceObserver
 *
 * @package Codilar\CheckoutEbs\Observer
 */
class BeforeOrderPlaceObserver implements ObserverInterface
{
    /**
     * Execute
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {

        $order = $observer->getOrder();
        if ($order->getPayment()->getMethod() == Ebs::CODE) {
            $order->setCanSendNewEmailFlag(false);
        }


    }
}