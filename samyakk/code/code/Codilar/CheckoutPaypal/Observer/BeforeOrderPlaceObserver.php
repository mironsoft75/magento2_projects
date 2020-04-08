<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Observer;


use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Codilar\CheckoutPaypal\Model\Payment\CheckoutPaypal;

class BeforeOrderPlaceObserver implements ObserverInterface
{

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        $order = $observer->getOrder();
        if ($order->getPayment()->getMethod() == CheckoutPaypal::CODE) {
            $order->setCanSendNewEmailFlag(false);
        }
    }
}