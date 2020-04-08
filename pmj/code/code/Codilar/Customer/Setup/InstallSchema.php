<?php

namespace Codilar\Customer\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package Codilar\Customer\Setup
 */
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

        //START table setup
        $table = $installer->getConnection()->newTable(
            $installer->getTable('codilar_customer_otp')
        )->addColumn(
            'codilar_customer_otp_id',
            Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'ID'
        )->addColumn(
            'customer_id',
            Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, 'unsigned' => true ],
            'Customer ID'
        )->addColumn(
            'mobile_number',
            Table::TYPE_TEXT,
            null,
            [ 'nullable' => false ],
            'Mobile Number'
        )->addColumn(
            'status',
            Table::TYPE_SMALLINT,
            null,
            [ 'nullable' => false, 'default' => 0 ],
            'OTP Verification Status'
        )->addForeignKey(
            'codilar_customer_otp_customer_id',
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment("Codilar Customer Mobile Number Temporary Table");
        $installer->getConnection()->createTable($table);
        //END   table setup
        $installer->endSetup();
    }
}
