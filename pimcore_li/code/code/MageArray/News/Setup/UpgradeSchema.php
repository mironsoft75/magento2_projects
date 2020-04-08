<?php

namespace MageArray\News\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package MageArray\News\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function upgrade(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;
        $installer->startSetup();

        if (version_compare($context->getVersion(), '1.0.4') < 0) {
            $tableName = $installer
                ->getTable('magearray_news_post');
            if ($installer->getConnection()
                    ->isTableExists($tableName) == true
            ) {
                $connection = $installer->getConnection();
                $connection->addColumn(
                    $tableName,
                    'type',
                    [
                        'type' => Table::TYPE_TEXT,
                        'nullable' => true,
                        'comment' => 'Post Type'
                    ]
                );
                $connection->addColumn(
                    $tableName,
                    'parent_id',
                    [
                        'type' => Table::TYPE_INTEGER,
                        'nullable' => false,
                        'default' => 0,
                        'comment' => 'Revision Type Parent Id'
                    ]
                );
            }
        }
		
		if (version_compare($context->getVersion(), '1.0.5') < 0) {
			 $tableName = $installer->getTable('magearray_news_post');
			if ($setup->getConnection()->isTableExists($tableName) == true) {
				$connection = $setup->getConnection();
				$connection->changeColumn(
                    $tableName,
                    'created_at',
                    'created_at',
                    [
						'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 
						'nullable' => false, 
						'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
					],
                    'Created At'
                );
				
				$connection->changeColumn(
                    $tableName,
                    'update_time',
                    'update_time',
                    [
						'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, 
						'nullable' => false, 
						'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_UPDATE
					],
                    'Update Time'
                );
			}
        }

        $installer->endSetup();
    }
}
