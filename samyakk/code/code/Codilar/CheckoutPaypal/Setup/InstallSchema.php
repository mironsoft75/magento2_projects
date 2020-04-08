<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Codilar\CheckoutPaypal\Model\ResourceModel\CheckoutPaypal as CheckoutPaypalResouceModel;


class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for a module
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     * @throws \Zend_Db_Exception
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $tableName = $setup->getTable(CheckoutPaypalResouceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                CheckoutPaypalResouceModel::ID_FIELD_NAME,
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
                'order_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Order ID'
            )->addColumn(
                'paypal_pay_id',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Paypal Pay ID'
            )->addColumn(
                'paypal_token',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Paypal Token'
            )->setComment("Codilar Checkout Paypal Payments Table");
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}