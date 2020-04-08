<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\ResetPassword\Setup;


use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;
use Codilar\ResetPassword\Model\ResourceModel\ResetPassword as ResetPasswordResouceModel;


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

        $tableName = $setup->getTable(ResetPasswordResouceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                ResetPasswordResouceModel::ID_FIELD_NAME,
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
                'customer_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Customer ID'
            )->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Customer Email'
            )->addColumn(
                'reset_token',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Reset Token'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable'  =>  false,
                    'default'   =>  Table::TIMESTAMP_INIT
                ],
                'Created At'
            )->setComment("Codilar Reset Password Table");
        $setup->getConnection()->createTable($table);

        $setup->endSetup();
    }
}