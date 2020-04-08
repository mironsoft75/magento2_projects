<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Codilar\Store\Model\ResourceModel\CurrencySwitcher;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Upgrades DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            $setup->startSetup();

            $tableName = $setup->getTable(CurrencySwitcher::TABLE_NAME);
            $table = $setup->getConnection()->newTable($tableName)
                ->addColumn(
                    CurrencySwitcher::ID_FIELD_NAME,
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'primary' => true,
                        'nullable' => false
                    ],
                    'Entity ID'
                )->addColumn(
                    'quote_id',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false
                    ],
                    'Quote Id'
                )->addColumn(
                    'quote_currency',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false
                    ],
                    'Quote Currency'
                )->addColumn(
                    'update_currency_to',
                    Table::TYPE_TEXT,
                    255,
                    [
                        'nullable' => false
                    ],
                    'Update Currency To'
                )->setComment("Codilar Cureency Switcher Table");
            $setup->getConnection()->createTable($table);

            $setup->endSetup();
        }
    }
}