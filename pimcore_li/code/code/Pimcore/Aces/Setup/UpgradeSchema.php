<?php

namespace Pimcore\Aces\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Pimcore\Aces\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class UpgradeSchema
 * @package Pimcore\YmmImport\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var Data
     */
    private $helper;

    /**
     * UpgradeSchema constructor.
     * @param LoggerInterface $logger
     * @param Data            $helper
     */
    public function __construct(
        LoggerInterface $logger,
        Data $helper
    )
    {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            //Create Table for PCES data with magento product id and Base vehicle id mapping
            /* Create table pimcore_aces_products */
            $this->createAcesProductsTable($installer);
        }
        $installer->endSetup();
    }

    private function createAcesProductsTable($installer)
    {
        $installer->getConnection()->dropTable($installer->getTable('pimcore_aces_products'));
        $table = $installer->getConnection()->newTable(
            $installer->getTable('pimcore_aces_products')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'ID'
        )->addColumn(
            'base_vehicle_id',
            Table::TYPE_INTEGER,
            10,
            ['unsigned' => true],
            'Base Vehicle ID'
        )->addColumn(
            'sku',
            Table::TYPE_TEXT,
            64,
            ['nullable' => false],
            'Product SKU'
        )->addColumn(
            'year_id',
            Table::TYPE_INTEGER,
            4,
            ['unsigned' => true],
            'Year'
        )->addColumn(
            'make_name',
            Table::TYPE_TEXT,
            '',
            ['nullable' => false],
            'Make Name'
        )->addColumn(
            'model_name',
            Table::TYPE_TEXT,
            '',
            ['nullable' => false],
            'Model Name'
        )->addColumn(
            'sub_model',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Sub Model Name'
        )->addColumn(
            'sub_detail',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Sub Detail'
        )->addColumn(
            'body_type',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Body Type'
        )->addColumn(
            'bed_type',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Bed Type'
        )->addColumn(
            'cab_type',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Cab Type'
        )->addColumn(
            'bed_length',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Bed Length'
        )->addColumn(
            'no_of_doors',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Number of Doors'
        )->addColumn(
            'fitment_position',
            Table::TYPE_TEXT,
            '',
            ['nullable' => true, 'default' => null],
            'Position'
        )->addForeignKey(
            $installer->getFkName('pimcore_aces_products', 'base_vehicle_id', 'pimcore_aces_ymm', 'base_vehicle_id'),
            'base_vehicle_id',
            $installer->getTable('pimcore_aces_ymm'),
            'base_vehicle_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName('pimcore_aces_products', 'sku', 'catalog_product_entity', 'sku'),
            'sku',
            $installer->getTable('catalog_product_entity'),
            'sku',
            Table::ACTION_CASCADE
        )->setComment(
            'pimcore aces products mapping by sku'
        );

        $installer->getConnection()->createTable($table);

    }

}