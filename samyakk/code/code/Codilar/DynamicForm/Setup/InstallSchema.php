<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Setup;


use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form as FormResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element as FormElementResourceModel;

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

        $this->createFormElementTable($setup);
        $this->createFormTable($setup);

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createFormElementTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(FormElementResourceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                FormElementResourceModel::ID_FIELD_NAME,
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
                'identifier',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Identifier'
            )->addColumn(
                'label',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Label'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Name'
            )->addColumn(
                'type',
                Table::TYPE_INTEGER,
                2,
                [
                    'nullable'  =>  false,
                    'default'   =>  0
                ],
                'Type. 0: Text, 1: Textarea, 2: Password, 3: Email, 4: File, 5: Select, 6: Multiselect, 7: Checkbox, 8: Radio'
            )->addColumn(
                'class_name',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable'  =>  false
                ],
                'Class Name'
            )->addColumn(
                'options_json',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable'  =>  false
                ],
                'Options JSON'
            )->addColumn(
                'store_views',
                Table::TYPE_SMALLINT,
                5,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Store IDs'
            )->addIndex(
                $setup->getIdxName(
                    $tableName,
                    ['identifier', 'store_views'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['identifier', 'store_views'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment("Codilar Dynamic Form Element Table");
        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createFormTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(FormResourceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                FormResourceModel::ID_FIELD_NAME,
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
                'identifier',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Identifier'
            )->addColumn(
                'title',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Title'
            )->addColumn(
                'content',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'CMS Content'
            )->addColumn(
                'form_element_ids',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable'  =>  false
                ],
                'Form Element IDs'
            )->addColumn(
                'form_placement',
                Table::TYPE_INTEGER,
                2,
                [
                    'nullable'  =>  false,
                    'default'   =>  0
                ],
                'Form Placement. 0: Top, 1: Bottom, 2: Left, 3: Right'
            )->addColumn(
                'is_active',
                Table::TYPE_INTEGER,
                1,
                [
                    'nullable'  =>  false,
                    'default'   =>  0
                ],
                'Is Active. 0: Inactive, 1: Active'
            )->addColumn(
                'store_views',
                Table::TYPE_SMALLINT,
                5,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Store IDs'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable'  =>  false,
                    'default'   =>  Table::TIMESTAMP_INIT
                ],
                'Created At'
            )->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable'  =>  false,
                    'default'   =>  Table::TIMESTAMP_INIT_UPDATE
                ],
                'Updated At'
            )->addIndex(
                $setup->getIdxName(
                    $tableName,
                    ['identifier', 'store_views'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                ['identifier', 'store_views'],
                ['type' => AdapterInterface::INDEX_TYPE_UNIQUE]
            )->setComment("Codilar Dynamic Form Table");
        $setup->getConnection()->createTable($table);
    }
}