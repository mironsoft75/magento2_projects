<?php

namespace Codilar\Migration\Model;

use Codilar\Migration\Model\Migration\Types\MigrationTypeInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;

class OrdersImport implements MigrationTypeInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Csv
     */
    private $csv;
    /**
     * @var ResourceConnection
     */
    private $resourceConnection;

    /**
     * OrdersImport constructor.
     * @param Filesystem $filesystem
     * @param Csv $csv
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        Filesystem $filesystem,
        Csv $csv,
        ResourceConnection $resourceConnection
    ) {
        $this->filesystem = $filesystem;
        $this->csv = $csv;
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @return string
     */
    public function startMigration()
    {

        $ordermap = $this->insertIntoSalesOrder();
        $this->insertIntoSalesOrderPayment($ordermap);
        $this->insertIntoSalesOrderAddress($ordermap);
        $this->insertIntoSalesOrdersHistory($ordermap);
        $this->insertIntoSalesOrderItem($ordermap);
        return "Orders Data Completed\n";
    }

    protected function getCsvData($fileName)
    {
        $reader = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
        return $this->csv->getData($reader->getAbsolutePath($fileName));
    }

    protected function insertIntoSalesOrder()
    {
        /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
        $connection = $this->resourceConnection->getConnection();
        $salesOrder = $this->getCsvData('sales_order.csv');
        if (count($salesOrder)) {
            $headers = $salesOrder[0];
            unset($salesOrder[0]);
        }
        $data = [];
        foreach ($salesOrder as $value) {
            if (count($value) === count($headers)) {
                $data[] = array_combine($headers, $value);
            }
        }

        $orderMap = [];
        foreach ($data as $datum) {
            unset($datum['entity_id']);
            $sql = $connection->select()
                        ->from('customer_entity')
                        ->where($connection->quoteInto('email = ?', $datum['customer_email']));
            $customer = $connection->fetchRow($sql);
            if ($customer) {
                $datum['customer_id'] = $customer['entity_id'];
            } else {
                unset($datum['customer_id']);
            }

            $incrementId = $datum['increment_id'];

            $datum['increment_id'] = str_replace("#", "", $datum['increment_id']);
            $datum['store_id'] = 1;

            $connection->insert(
                'sales_order',
                $datum
            );

            $orderMap[$incrementId] = $connection->lastInsertId('sales_order');
        }
        echo  "Orders Completed..!\n";
        return $orderMap;
    }

    protected function insertIntoSalesOrderPayment($orderMap)
    {
        /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
        $connection = $this->resourceConnection->getConnection();
        $salesOrderPayment = $this->getCsvData('sales_order_payment.csv');
        if (count($salesOrderPayment)) {
            $headers = $salesOrderPayment[0];
            unset($salesOrderPayment[0]);
        }
        $data = [];
        foreach ($salesOrderPayment as $value) {
            if (count($value) === count($headers)) {
                $data[] = array_combine($headers, $value);
            }
        }

        foreach ($data as $datum) {
            unset($datum['entity_id']);
            $datum['parent_id'] = $orderMap[$datum['increment_id']];
            unset($datum['increment_id']);
            $connection->insert(
                'sales_order_payment',
                $datum
            );
        }
        echo "Sales Order Payments Completed..!\n";
    }

    protected function insertIntoSalesOrderAddress($orderMap)
    {
        /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
        $connection = $this->resourceConnection->getConnection();
        $salesOrderAddress = $this->getCsvData('sales_order_address.csv');
        if (count($salesOrderAddress)) {
            $headers = $salesOrderAddress[0];
            unset($salesOrderAddress[0]);
        }
        $data = [];
        foreach ($salesOrderAddress as $value) {
            if (count($value) === count($headers)) {
                $data[] = array_combine($headers, $value);
            }
        }

        foreach ($data as $datum) {
            unset($datum['entity_id']);
            $datum['parent_id'] = $orderMap[$datum['increment_id']];
            unset($datum['increment_id']);
            $connection->insert(
                'sales_order_address',
                $datum
            );
        }
        echo "Sales Order Address Completed..!\n";
    }

    protected function insertIntoSalesOrderItem($orderMap)
    {
        /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
        $connection = $this->resourceConnection->getConnection();
        $salesOrderItem = $this->getCsvData('sales_order_item.csv');
        if (count($salesOrderItem)) {
            $headers = $salesOrderItem[0];
            unset($salesOrderItem[0]);
        }
        $data = [];
        foreach ($salesOrderItem as $value) {
            if (count($value) === count($headers)) {
                $data[] = array_combine($headers, $value);
            }
        }

        foreach ($data as $datum) {
            unset($datum['item_id']);
            $datum['order_id'] = $orderMap[$datum['increment_id']];
            $datum['store_id'] = 1;
            $productOptions = $datum['product_options'];
            $unserializedProductOptions = @unserialize($productOptions);
            if ($unserializedProductOptions) {
                $productOptions = \json_encode($unserializedProductOptions);
            }
            $datum['product_options'] = $productOptions;
            unset($datum['increment_id']);
            $connection->insert(
                'sales_order_item',
                $datum
            );
        }
        echo "Sales Order Item Completed..!\n";
    }

    protected function insertIntoSalesOrdersHistory($orderMap)
    {
        /** @var \Magento\Framework\DB\Adapter\Pdo\Mysql $connection */
        $connection = $this->resourceConnection->getConnection();
        $salesOrderHistory = $this->getCsvData('sales_order_status_history.csv');
        if (count($salesOrderHistory)) {
            $headers = $salesOrderHistory[0];
            unset($salesOrderHistory[0]);
        }
        $data = [];
        foreach ($salesOrderHistory as $value) {
            if (count($value) === count($headers)) {
                $data[] = array_combine($headers, $value);
            }
        }

        foreach ($data as $datum) {
            unset($datum['entity_id']);
            $datum['parent_id'] = $orderMap[$datum['increment_id']];
            unset($datum['increment_id']);
            $connection->insert(
                'sales_order_status_history',
                $datum
            );
        }
        echo "Sales Order History Completed..!\n";
    }
}
