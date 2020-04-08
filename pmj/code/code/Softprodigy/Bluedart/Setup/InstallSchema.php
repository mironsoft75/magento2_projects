<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Softprodigy\Bluedart\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        /**
         * Create table 'dailydeal'
         * 
         */
        /*->addColumn(
                'sold',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'default'=> '0'],
                'deal sold'
            )*/
        if (!$installer->tableExists('bluedart_awb_list')) {
            $table = $installer->getConnection()->newTable(
                $installer->getTable('bluedart_awb_list')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'row Id'
            )->addColumn(
                'awb_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                110,
                ['nullable' => true],
                'awb number'
            )->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'order id'
            )->addColumn(
                'order_increment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'order increment id'
            )->addColumn(
                'city_state',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                155,
                ['nullable' => true],
                'city or state'
            )->addColumn(
                'product_details',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => true],
                'awb products detail'
            )->addColumn(
                'awb_weight',
                \Magento\Framework\DB\Ddl\Table::TYPE_FLOAT,
                null,
                ['nullable' => false],
                'awb weight'
            )->addColumn(
                'awb_date',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'awb date time'
            )->addColumn(
                'created_on',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'created at'
            )->setComment(
                'bluedart awb list'
            );
            $installer->getConnection()->createTable($table);
        }
        ////2
        if (!$installer->tableExists('bluedart_manifest')) {
            $table2 = $installer->getConnection()->newTable(
                $installer->getTable('bluedart_manifest')
            )->addColumn(
                'id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'row Id'
            )->addColumn(
                'order_count',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                null,
                ['nullable' => false],
                'order count'
            )->addColumn(
                'batch_number',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                155,
                ['nullable' => true],
                'batch number'
            )->addColumn(
                'file_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                155,
                ['nullable' => true],
                'file name'
            )->addColumn(
                'gen_from',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'genrated from'
            )->addColumn(
                'gen_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'genrated to'
            )->addColumn(
                'created_on',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'created at'
            )->setComment(
                'bluedart manifest Table'
            );

            $installer->getConnection()->createTable($table2);
        }
         
        $installer->endSetup();
    }
}
