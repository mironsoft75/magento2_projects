<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 7/11/18
 * Time: 4:05 PM
 */
namespace Codilar\StoneAndMetalRates\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\App\Filesystem\DirectoryList;


class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

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
            [ 'nullable' => false],
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
                null,
                [],
                'Rate of Metal/Stone'
        )->addColumn(
            'unit',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [],
            'Unit'
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

        $installer->endSetup();
    }
}


