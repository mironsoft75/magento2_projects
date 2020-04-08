<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutCcAvenue\Observer;


use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Codilar\CheckoutCcAvenue\Model\CcAvenue;

class BeforeOrderPlaceObserver implements ObserverInterface
{

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        if ($order->getPayment()->getMethod() == CcAvenue::CODE) {
            $order->setCanSendNewEmailFlag(false);
        }
    }
}