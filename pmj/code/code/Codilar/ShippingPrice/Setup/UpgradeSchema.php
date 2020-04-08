<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/4/19
 * Time: 3:37 PM
 */

namespace Codilar\ShippingPrice\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Codilar\Appointment\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.1.6', '<')) {
            $table = $installer->getTable('shipping_tablerate');
            $columns = [
                'days' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Days',
                ],

            ];

            $connection = $installer->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($table, $name, $definition);
            }

            $installer->endSetup();
        }
        if (version_compare($context->getVersion(), '1.1.7', '<')) {
            $table = $installer->getTable('shipping_tablerate');
            $columns = [
                'city' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'City',
                ],

            ];

            $connection = $installer->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($table, $name, $definition);
            }

            $installer->endSetup();
        }
        if (version_compare($context->getVersion(), '1.1.8', '<')) {
            $table = $installer->getTable('sales_order_item');
            $columns = [
                'estimated_days' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Estimated Days',
                ],

            ];

            $connection = $installer->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($table, $name, $definition);
            }

            $installer->endSetup();
        }
    }
}