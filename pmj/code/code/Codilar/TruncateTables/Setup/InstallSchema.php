<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 26/4/19
 * Time: 1:01 PM
 */

namespace Codilar\TruncateTables\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Psr\Log\LoggerInterface;

/**
 * Class InstallSchema
 * @package Codilar\TruncateTables\Setup
 */
class InstallSchema implements InstallSchemaInterface
{


    /**
     * @var LoggerInterface
     */
    private $_logger;

    /**
     * InstallSchema constructor.
     * @param LoggerInterface $logger
     */
    public function __construct
    (
        LoggerInterface $logger

    )
    {
        $this->_logger = $logger;

    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        try {
            $connection = $setup->getConnection();
            $start = "SET FOREIGN_KEY_CHECKS = 0";
            $connection->query($start);
            $tables = array("gift_message", "quote", "quote_address", "quote_address_item", "quote_id_mask", "quote_item", "quote_item_option", "quote_payment", "quote_shipping_rate", "reporting_orders", "sales_bestsellers_aggregated_daily", "sales_bestsellers_aggregated_monthly", "sales_bestsellers_aggregated_yearly", "sales_creditmemo", "sales_creditmemo_comment", "sales_creditmemo_grid", "sales_creditmemo_item", "sales_invoice", "sales_invoiced_aggregated", "sales_invoiced_aggregated_order", "sales_invoice_comment", "sales_invoice_grid", "sales_invoice_item", "sales_order", "sales_order_address", "sales_order_aggregated_created", "sales_order_aggregated_updated", "sales_order_grid", "sales_order_item", "sales_order_payment", "sales_order_status_history", "sales_order_tax", "sales_order_tax_item", "sales_payment_transaction", "sales_refunded_aggregated", "sales_refunded_aggregated_order", "sales_shipment", "sales_shipment_comment", "sales_shipment_grid", "sales_shipment_item", "sales_shipment_track", "sales_shipping_aggregated", "sales_shipping_aggregated_order", "tax_order_aggregated_created", "tax_order_aggregated_updated", "customer_address_entity", "customer_address_entity_datetime", "customer_address_entity_decimal", "customer_address_entity", "customer_address_entity_datetime", "customer_address_entity_decimal", "customer_address_entity_int", "customer_address_entity_text", "customer_address_entity_varchar", "customer_entity", "customer_entity_datetime", "customer_entity_decimal", "customer_entity_int", "customer_entity_text", "customer_entity_varchar", "customer_grid_flat", "customer_log", "customer_visitor", "persistent_session", "wishlist", "wishlist_item", "wishlist_item_option");
            foreach ($tables as $name) {
                $tableName = $connection->getTableName($name);
                $sql = "TRUNCATE TABLE " . $tableName;
                $connection->query($sql);
            }
            $end = "SET FOREIGN_KEY_CHECKS = 1";
            $connection->query($end);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }


    }
}