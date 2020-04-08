<?php
namespace Codilar\ContactUs\Setup;
use Magento\Framework\DB\Ddl\Table;

/**
 * Class UpgradeSchema
 * @package Codilar\ContactUs\Setup
 */
class UpgradeSchema implements \Magento\Framework\Setup\UpgradeSchemaInterface
{
    /**
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function upgrade(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        //creating magento_contact_us table
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $table = $installer->getConnection()
                ->newTable($installer->getTable('magento_contact_us'))
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                    'Rewrite Id'
                )
                ->addColumn(
                    'name',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Customer Name'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Customer Email'
                )
                ->addColumn(
                    'phone_number',
                    Table::TYPE_TEXT,
                    null,
                    [],
                    'Phone Number'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    null,
                    ['nullable' => false, 'default' => ''],
                    'Customer Message'
                )
                ->addColumn(
                    'store_id',
                    Table::TYPE_SMALLINT,
                    null,
                    ['unsigned' => true, 'nullable' => false],
                    'Store Id'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Created At'
                )
                ->setComment('Magento Customer Contact us');
            $installer->getConnection()->createTable($table);
        }
		//END   table setup
		$installer->endSetup();
    }
}
