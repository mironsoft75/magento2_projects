<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 13/5/19
 * Time: 6:39 PM
 */

namespace Codilar\MasterTables\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'codilar_variant_name'
         */
        if (!$installer->tableExists('codilar_variant_name')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('codilar_variant_name'))
                ->addColumn(
                    'variant_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Variant Id'
                )->addColumn(
                    'variant_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Variant Name'
                )
                ->addColumn(
                    'product_type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Product Type'
                )
                ->addColumn(
                    'category',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Category'
                )
                ->addColumn(
                    'sub_category',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'Sub Category'
                )
                ->addColumn(
                    'sub_sub_category',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Sub sub Category'
                )
                ->addColumn(
                    'gold_diamond_uncut',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Gold Diamond Uncut'
                )
                ->addColumn(
                    'display_in_ui',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Display in UI'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->setComment('Codilar Variant Name');
            $installer->getConnection()->createTable($table);
        }
        /**
         * Create table 'codilar_metal'
         */
        if (!$installer->tableExists('codilar_metal')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('codilar_metal'))
                ->addColumn(
                    'metal_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Variant Id'
                )
                ->addColumn(
                    'karat_color',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Karat Color'
                )
                ->addColumn(
                    'metal_type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Metal Type'
                )
                ->addColumn(
                    'karat',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'Karat'
                )
                ->addColumn(
                    'metal_color',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Metal Color'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->setComment('Codilar Metal');
            $installer->getConnection()->createTable($table);
        }
        /**
         * Create table 'codilar_location_name'
         */
        if (!$installer->tableExists('codilar_location_name')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('codilar_location_name'))
                ->addColumn(
                    'location_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Variant Id'
                )
                ->addColumn(
                    'location_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Location_Name'
                )
                ->addColumn(
                    'dept',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Dept'
                )
                ->addColumn(
                    'show_as_instock',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Show As InStock'
                )
                ->addColumn(
                    'customer_friendly_location_name',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'Customer Friendly Location Name'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->setComment('Codilar Location Name');
            $installer->getConnection()->createTable($table);
        }
        /**
         * Create table 'codilar_stone_bom'
         */
        if (!$installer->tableExists('codilar_stone_bom')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('codilar_stone_bom'))
                ->addColumn(
                    'stone_bom_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Variant Id'
                )
                ->addColumn(
                    'stone_bom_variant',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Bom Variant'
                )
                ->addColumn(
                    'stone_type',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Stone Type'
                )
                ->addColumn(
                    'stone_quality',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'Stone Quality'
                )
                ->addColumn(
                    'stone_color',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Stone Color'
                )
                ->addColumn(
                    'wt_range',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Wt Range'
                )
                ->addColumn(
                    'indian_rate_carat',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Indian Rate/carat'
                )
                ->addColumn(
                    'usa_rate_carat',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'USA Rate/carat'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->setComment('Codilar Stone BOM');
            $installer->getConnection()->createTable($table);
        }
        /**
         * Create table 'codilar_metal_bom'
         */
        if (!$installer->tableExists('codilar_metal_bom')) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('codilar_metal_bom'))
                ->addColumn(
                    'metal_bom_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Variant Id'
                )
                ->addColumn(
                    'metal_bom_variant',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Bom Variant'
                )
                ->addColumn(
                    'item_code',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Item Code'
                )
                ->addColumn(
                    'purity',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    [],
                    'Purity'
                )
                ->addColumn(
                    'color',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Color'
                )
                ->addColumn(
                    'indian_rate_gram',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Indian Rate/gram'
                )
                ->addColumn(
                    'usa_rate_gram',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'USA Rate/gram'
                )
                ->addColumn(
                    'created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                    'Created At'
                )->addColumn(
                    'updated_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                    'Updated At'
                )
                ->setComment('Codilar Metal BOM');
            $installer->getConnection()->createTable($table);
        }
        $installer->endSetup();
    }
}