<?php
/**
 * Created by PhpStorm.
 * User: Navaneeth Vijay
 * Date: 8/26/2018
 * Time: 3:11 PM
 */

namespace Codilar\Videostore\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $table = $installer->getConnection()->newTable(
            $installer->getTable('codilar_videostore_request')
        )->addColumn(
            'videostore_request_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity ID'
        )->addColumn(
            'videostore_product_ids',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Full name'
        )->addColumn(
            'videostore_customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => true],
            'Videostore customer id'
        )->addColumn(
            'full_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Full name'
        )->addColumn(
            'email',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Email'
        )->addColumn(
            'mobile_number',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            ['nullable' => false],
            'Mobile Number'
        )->addColumn(
            'requested_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATE,
            255,
            ['nullable' => false],
            'Requested Date'
        )->addColumn(
            'requested_time',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            255,
            ['nullable' => false],
            'address'
        )->addColumn(
            'message',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Message'
        )->addColumn(
            'assigned_to',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Assigned To'
        )->addColumn(
            'videostore_request_created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            255,
            ['nullable' => false],
            'Videostore Request Created at'
        )->addColumn(
            'videostore_request_updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            255,
            ['nullable' => false],
            'Videostore Request Updated at'
        )
            ->addColumn(
                'videostore_request_status',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Videostore Request Status'
            )->setComment(
                'Row Data Table'
            );
        $installer->getConnection()->createTable($table);
        /**
         * Creating videostorecart product table
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('codilar_videostore_cart')
        )->addColumn(
            'videostore_cart_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false],
            'videostore product id'
        )->addColumn(
            'videostore_customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'videostore customer id'
        )->addColumn(
            'videostore_customer_session_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'videostore customer session id'
        )->addForeignKey(
            $installer->getFkName('codilar_videostore_cart', 'product_id', 'catalog_product_entity', 'entity_id'),
            'product_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )->setComment(
            'Row Data Table'
        );
        $installer->getConnection()->createTable($table);
        /*
         * Create codilar videostore request activity
         */
        $table = $installer->getConnection()->newTable(
            $installer->getTable('codilar_videostore_request_activity')
        )->addColumn(
            'videostore_request_activity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Videstore request activity Id'
        )->addColumn(
            'videostore_request_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'videostore request id'
        )->addColumn(
            'videostore_request_activity',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'videostore request activity '
        )->setComment(
            'Row Data Table'
        );
        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }

}