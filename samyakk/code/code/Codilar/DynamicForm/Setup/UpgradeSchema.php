<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Setup;


use Codilar\DynamicForm\Api\Data\FormInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form as FormResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element as ElementResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response as ResponseResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field as ResponseFieldResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element;
use Codilar\DynamicForm\Api\Data\Form\ElementInterface;

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
        $setup->startSetup();

        $this->createFormResponseTable($setup);
        $this->createFormResponseFieldTable($setup);

        if (version_compare($context->getVersion(), '1.0.2') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable(FormResourceModel::TABLE_NAME),
                FormInterface::RESPONSE_MESSAGE,
                [
                    'nullable' => false,
                    'type'     => Table::TYPE_TEXT,
                    'comment'  => 'Form response message on submit'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.3') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable(FormResourceModel::TABLE_NAME),
                FormInterface::FORM_CSS,
                [
                    'nullable' => true,
                    'type'     => Table::TYPE_TEXT,
                    'comment'  => 'Form Custom CSS'
                ]
            );
            $setup->getConnection()->addColumn(
                $setup->getTable(Element::TABLE_NAME),
                "validation_json",
                [
                    'nullable' => false,
                    'type'     => Table::TYPE_TEXT,
                    'comment'  => 'Element Validation'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.5') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable(ElementResourceModel::TABLE_NAME),
                ElementInterface::CUSTOM_HTML,
                [
                    'nullable' => false,
                    'type'     => Table::TYPE_TEXT,
                    'comment'  => 'Custom HTML for element'
                ]
            );
        }

        if (version_compare($context->getVersion(), '1.0.6') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable(FormResourceModel::TABLE_NAME),
                FormInterface::EMAIL_TEMPLATE,
                [
                    'nullable' => false,
                    'type' => Table::TYPE_TEXT,
                    'comment' => 'Email Template'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.7') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable(FormResourceModel::TABLE_NAME),
                FormInterface::EMAIL_SENDER,
                [
                    'nullable' => false,
                    'type'     => Table::TYPE_TEXT,
                    'size'     => 255,
                    'comment'  => 'Email Sender'
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.8') < 0) {
            $setup->getConnection()->addColumn(
                $setup->getTable(FormResourceModel::TABLE_NAME),
                FormInterface::SEND_EMAIL_COPY_TO,
                [
                    'nullable' => false,
                    'type'     => Table::TYPE_TEXT,
                    'size'     => 255,
                    'comment'  => 'Send Email Copy To'
                ]
            );
        }



        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createFormResponseTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(ResponseResourceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                ResponseResourceModel::ID_FIELD_NAME,
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
                'form_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Form ID'
            )->addColumn(
                'customer_email',
                Table::TYPE_TEXT,
                1000,
                [
                    'nullable' => false
                ],
                'Customer Email'
            )->addColumn(
                'customer_ip',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Customer Email'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable'  =>  false,
                    'default'   =>  Table::TIMESTAMP_INIT
                ],
                'Created At'
            )->addForeignKey(
                $setup->getFkName(
                    $tableName,
                    'form_id',
                    $setup->getTable(FormResourceModel::TABLE_NAME),
                    FormResourceModel::ID_FIELD_NAME
                ),
                'form_id',
                $setup->getTable(FormResourceModel::TABLE_NAME),
                FormResourceModel::ID_FIELD_NAME,
                Table::ACTION_CASCADE
            )->setComment("Codilar Dynamic Form Response Table");
        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createFormResponseFieldTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(ResponseFieldResourceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                ResponseFieldResourceModel::ID_FIELD_NAME,
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
                'form_response_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Form Response ID'
            )->addColumn(
                'type',
                Table::TYPE_TEXT,
                1000,
                [
                    'nullable' => false
                ],
                'Form Element Type. 0: Text, 1: Textarea, 2: Password, 3: Email, 4: File, 5: Select, 6: Multiselect, 7: Checkbox, 8: Radio'
            )->addColumn(
                'name',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Form Element Type'
            )->addColumn(
                'value',
                Table::TYPE_TEXT,
                null,
                [
                    'nullable' => false
                ],
                'Value'
            )->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                [
                    'nullable'  =>  false,
                    'default'   =>  Table::TIMESTAMP_INIT
                ],
                'Created At'
            )->addForeignKey(
                $setup->getFkName(
                    $tableName,
                    'form_response_id',
                    $setup->getTable(ResponseResourceModel::TABLE_NAME),
                    ResponseResourceModel::ID_FIELD_NAME
                ),
                'form_response_id',
                $setup->getTable(ResponseResourceModel::TABLE_NAME),
                ResponseResourceModel::ID_FIELD_NAME,
                Table::ACTION_CASCADE
            )->setComment("Codilar Dynamic Form Response Field Table");
        $setup->getConnection()->createTable($table);
    }
}