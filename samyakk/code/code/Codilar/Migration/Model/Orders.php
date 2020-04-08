<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Migration\Model;

use Codilar\Migration\Helper\MigrationCsv;
use Codilar\Migration\Model\Migration\Types\MigrationTypeInterface;
use Magento\Sales\Model\ResourceModel\Order\Address\Collection as AddressCollection;
use Magento\Sales\Model\ResourceModel\Order\Collection as OrderCollection;
use Magento\Sales\Model\ResourceModel\Order\Item\Collection as SalesOrderItemCollection;
use Magento\Sales\Model\ResourceModel\Order\Payment\Collection as PaymentCollection;
use Magento\Sales\Model\ResourceModel\Order\Status\History\Collection as HistoryCollection;

class Orders implements MigrationTypeInterface
{
    /**
     * @var OrderCollection
     */
    private $orderCollection;
    /**
     * @var SalesOrderItemCollection
     */
    private $salesOrderItemCollection;
    /**
     * @var PaymentCollection
     */
    private $paymentCollection;
    /**
     * @var AddressCollection
     */
    private $addressCollection;
    /**
     * @var MigrationCsv
     */
    private $migrationCsv;
    /**
     * @var HistoryCollection
     */
    private $historyCollection;

    /**
     * Orders constructor.
     * @param OrderCollection $orderCollection
     * @param SalesOrderItemCollection $salesOrderItemCollection
     * @param PaymentCollection $paymentCollection
     * @param AddressCollection $addressCollection
     * @param MigrationCsv $migrationCsv
     * @param HistoryCollection $historyCollection
     */
    public function __construct(
        OrderCollection $orderCollection,
        SalesOrderItemCollection $salesOrderItemCollection,
        PaymentCollection $paymentCollection,
        AddressCollection $addressCollection,
        MigrationCsv $migrationCsv,
        HistoryCollection $historyCollection
    ) {
        $this->orderCollection = $orderCollection;
        $this->salesOrderItemCollection = $salesOrderItemCollection;
        $this->paymentCollection = $paymentCollection;
        $this->addressCollection = $addressCollection;
        $this->migrationCsv = $migrationCsv;
        $this->historyCollection = $historyCollection;
    }

    /**
     * @return string
     */
    public function startMigration()
    {
        // From sales_order Table
        $orders = $this->orderCollection->addAttributeToSelect('*');
        $headers = null;
        $ordersArray = [];
        /** @var \Magento\Sales\Model\Order $order */
        foreach ($orders as $order) {
            if (!$headers) {
                $headers = array_keys($order->getData());
                $ordersArray[] = $headers;
            }
            $orderData = $order->getData();
            $orderData['increment_id'] = (string)'#'.$order->getIncrementId();
            $ordersArray[$order->getId()] = $orderData;
        }

        $orderFileName = $this->migrationCsv->generateCsv($ordersArray, "sales_order.csv");

        // From sales_order_payment Table
        $payments = $this->paymentCollection->addAttributeToSelect('*');
        $headers = null;
        $paymentsArray = [];
        /** @var \Magento\Sales\Model\Order\Payment $item */
        foreach ($payments as $payment) {
            if (!$headers) {
                $headers = array_keys($payment->getData());
                $headers[] = 'increment_id';
                $paymentsArray[] = $headers;
            }
            $paymentData = $payment->getData();
            if (!array_key_exists($payment->getParentId(), $ordersArray)) {
                continue;
            }
            $paymentData['increment_id'] = $ordersArray[$payment->getParentId()]['increment_id'];
            $paymentData['additional_information'] = json_encode($paymentData['additional_information']);
            $paymentsArray[] = $paymentData;
        }

        $paymentsFileName = $this->migrationCsv->generateCsv($paymentsArray, "sales_order_payment.csv");

        // From sales_order_address Table
        $addressCollection = $this->addressCollection->addAttributeToSelect('*');
        $headers = null;
        $addressArray = [];
        /** @var \Magento\Sales\Model\Order\Address $item */
        foreach ($addressCollection as $address) {
            if (!$headers) {
                $headers = array_keys($address->getData());
                $headers[] = 'increment_id';
                $addressArray[] = $headers;
            }
            $addressData = $address->getData();
            if (!array_key_exists($address->getParentId(), $ordersArray)) {
                continue;
            }
            $addressData['increment_id'] = $ordersArray[$address->getParentId()]['increment_id'];
            $addressArray[] = $addressData;
        }

        $addressFileName = $this->migrationCsv->generateCsv($addressArray, "sales_order_address.csv");

        echo "Items Staring...\n";
        // From sales_order_item Table
        $items = $this->salesOrderItemCollection->addAttributeToSelect('*');

        $items->getSelect()->joinLeft(['order' => 'sales_order'], 'order.entity_id = main_table.order_id', [])->where('order.entity_id IS NOT NULL');

        $headers = null;
        $itemsArray = [];
        $count = 0;
        /** @var \Magento\Sales\Model\Order\Item $item */
        foreach ($items as $item) {
            if (!$headers) {
                $headers = array_keys($item->getData());
                $headers[] = 'increment_id';
                $itemsArray[] = $headers;
            }

            $itemData = $item->getData();
            if (!array_key_exists($item->getOrderId(), $ordersArray)) {
                continue;
            }
            if ($item['parent_item_id'] != null) {
                continue;
            }
            $itemData['increment_id'] = $ordersArray[$item->getOrderId()]['increment_id'];
            $itemData['product_options'] = is_array($itemData['product_options']) ? \json_encode($itemData['product_options']) : $itemData['product_options'];
            $itemsArray[] = $itemData;
        }

        $itemsFileName = $this->migrationCsv->generateCsv($itemsArray, "sales_order_item.csv");

        // From sales_order_status_history Table
        $historyCollection = $this->historyCollection->addAttributeToSelect('*');
        $headers = null;
        $historyArray = [];
        /** @var \Magento\Sales\Model\Order\Status\History $item */
        foreach ($historyCollection as $history) {
            if (!$headers) {
                $headers = array_keys($history->getData());
                $headers[] = 'increment_id';
                $historyArray[] = $headers;
            }
            $historyData = $history->getData();
            if (!array_key_exists($history->getParentId(), $ordersArray)) {
                continue;
            }
            $historyData['increment_id'] = $ordersArray[$history->getParentId()]['increment_id'];
            $historyArray[] = $historyData;
        }

        $historyFileName = $this->migrationCsv->generateCsv($historyArray, "sales_order_status_history.csv");

        return $orderFileName . " and " . $paymentsFileName . " and " . $addressFileName . " and " . $itemsFileName . " and " . $historyFileName;
    }
}
