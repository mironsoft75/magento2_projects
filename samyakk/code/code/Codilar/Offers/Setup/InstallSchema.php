<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{


    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();
        $offersTable = $installer->getTable('codilar_offers');
        if (!$installer->tableExists($offersTable)){
            $table = $installer->getConnection()->newTable(
                $installer->getTable($offersTable)
            )
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Id'
            )->addColumn(
                'title',
                Table::TYPE_TEXT,
                null,
                [],
                'Offer Title'
            )
            ->addColumn(
                'cms_block_id',
                Table::TYPE_SMALLINT,
                6,
                [],
                'CMS Block Id'
            )->addColumn(
                'has_products',
                Table::TYPE_INTEGER,
                null,
                ["nullable" => false, "default" => 0],
                'CMS Block Id'
            )->addColumn(
                'block_data',
                Table::TYPE_TEXT,
                "2M",
                ['nullable' => true,],
                'Block Product Data'
            )->addColumn(
                'block_data_static',
                Table::TYPE_TEXT,
                "2M",
                ['nullable' => true,],
                'Block Static Data'
            )->addColumn(
                'start_date',
                Table::TYPE_DATETIME,
                null,
                [],
                'Offer Start Date'
            )->addColumn(
                'end_date',
                Table::TYPE_DATETIME,
                null,
                [],
                'Offer End Date'
            )->addColumn(
                'sort_order',
                Table::TYPE_INTEGER,
                null,
                [],
                'Offer Sort Order'
            )->addColumn(
                'created_at',
                Table::TYPE_DATETIME,
                null,
                [],
                'Offer Sort Order'
            )->addColumn(
                'is_active',
                Table::TYPE_INTEGER,
                null,
                ["default" => 0, "nullable" =>  false],
                'Offer Sort Order'
            )->addForeignKey(
                    'codilar_offers_cms_block_link',
                    'cms_block_id',
                    $installer->getTable('cms_block'),
                    'block_id',
                    Table::ACTION_CASCADE
                )
            ->setComment(
                'Codilar Offers table'
            );
            $installer->getConnection()->createTable($table);
        }
    }
}