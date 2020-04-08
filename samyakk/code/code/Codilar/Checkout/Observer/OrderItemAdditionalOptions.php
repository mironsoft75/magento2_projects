<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Observer;


use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;

class OrderItemAdditionalOptions implements ObserverInterface
{

    /**
     * @param Observer $observer
     * @return void
     * @throws LocalizedException
     */
    public function execute(Observer $observer)
    {
        try {
            /** @var \Magento\Quote\Model\Quote $quote */
            $quote = $observer->getEvent()->getData('quote');
            /** @var \Magento\Sales\Model\Order $order */
            $order = $observer->getEvent()->getData('order');
            $quoteItems = [];

            // Map Quote Item with Quote Item Id
            /** @var \Magento\Quote\Model\Quote\Item $quoteItem */
            foreach ($quote->getAllItems() as $quoteItem) {
                $quoteItems[$quoteItem->getId()] = $quoteItem;
            }

            /** @var \Magento\Sales\Model\Order\Item $orderItem */
            foreach ($order->getAllItems() as $orderItem) {
                $quoteItemId = $orderItem->getQuoteItemId();
                $quoteItem = $quoteItems[$quoteItemId];
                $additionalOptions = $quoteItem->getOptionByCode('additional_options');
                $additionalOptions = $additionalOptions ? \json_decode($additionalOptions->getValue(), true) : [];
                if (count($additionalOptions) > 0) {
                    // Get Order Item's other options
                    $options = $orderItem->getProductOptions();
                    // Set additional options to Order Item
                    $options['additional_options'] = $additionalOptions;
                    $orderItem->setProductOptions($options);
                }
            }
        } catch (\Exception $e) {
            throw new LocalizedException(__("Error placing order with the current cart item(s)"));
        }
    }
}