<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/11/18
 * Time: 2:53 PM
 */

namespace Codilar\StoneAndMetalRates\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;


class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade( SchemaSetupInterface $setup, ModuleContextInterface $context ) {
        $installer = $setup;

        $installer->startSetup();


        if(version_compare($context->getVersion(), '1.0.4', '<')) {
            $installer->getConnection()->dropTable($installer->getTable('stone_metal_rates'));
            $table = $installer->getConnection()->newTable(
                $installer->getTable('stone_metal_rates')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Grid Increment Id'
            )->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Store Id'
            )->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                255,
                ['nullable' => false],
                'Status'
            )->addColumn(
                'type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Metal or Stone'
            )->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                ' Name'
            )->addColumn(
                'rate',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                255,
                ['nullable' => false],
                'Rate of Metal/Stone'
            )->addColumn(
                'unit',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Unit'
            )->addColumn(
                'stone_name_for_customer',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Stone Name For Customer'
            )->addColumn(
                'stone_shape',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Stone Shape'
            )->addColumn(
                'stone_quality',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Stone Quality'
            )->addColumn(
                'metal_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Metal Type'
            )->addColumn(
                'metal_purity',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                255,
                ['nullable' => true],
                'Metal Purity'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                ],
                'Creation Time'
            )->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Modification Time'
            )->addIndex(
                $installer->getIdxName('stone_metal_rates', array('store_id', 'type', 'name', 'rate')),
                array('store_id', 'type', 'name', 'rate'),
                ['type' => 'unique']
            )->setComment(
                'Row Data Table'
            );

            $installer->getConnection()->createTable($table);
        }
        if(version_compare($context->getVersion(), '1.0.8', '<')) {
            $installer->getConnection()->dropTable($installer->getTable('rate_user_activity'));
            $table = $installer->getConnection()->newTable(
                $installer->getTable('rate_user_activity')
            )->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Increment Id'
            )->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'User Id'
            )->addColumn(
                'user_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'User Name'
            )->addColumn(
                    'data_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    255,
                    ['nullable' => false],
                    'Central Table Data Id'
                )->addColumn(
                'activity',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'Activity of User in Central Table'
            )->addColumn(
                'old_data',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'Old Data'
            )->addColumn(
                'new_data',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                '2M',
                ['nullable' => false],
                'New Data'
            )->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT,
                ],
                'Creation Time'
            )->addForeignKey(
                $installer->getFkName('rate_user_activity', 'data_id', 'stone_metal_rates', 'entity_id'),
                'data_id',
                $installer->getTable('stone_metal_rates'),
                'entity_id',
                Table::ACTION_CASCADE
            )->setComment(
                'Activity Table'
            );

            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}
