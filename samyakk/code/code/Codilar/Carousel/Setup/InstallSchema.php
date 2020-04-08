<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Setup;

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Codilar\Carousel\Model\ResourceModel\Carousel as CarouselResourceModel;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item as CarouselItemResourceModel;

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

        $this->createCarouselTable($setup);
        $this->createCarouselItemsTable($setup);

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createCarouselItemsTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(CarouselItemResourceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                CarouselItemResourceModel::ID_FIELD_NAME,
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
                'carousel_id',
                Table::TYPE_INTEGER,
                null,
                [
                    'unsigned' => true,
                    'nullable' => false
                ],
                'Carousel Entity ID'
            )->addColumn(
                'content',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Content'
            )->addColumn(
                'label',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Label'
            )->addColumn(
                'link',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Link'
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
                $setup->getFkName(CarouselItemResourceModel::TABLE_NAME, 'carousel_id', CarouselResourceModel::TABLE_NAME, CarouselResourceModel::ID_FIELD_NAME),
                'carousel_id',
                CarouselResourceModel::TABLE_NAME,
                CarouselResourceModel::ID_FIELD_NAME,
                Table::ACTION_CASCADE
            )->setComment("Codilar Dynamic Form Element Table");
        $setup->getConnection()->createTable($table);
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    protected function createCarouselTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable(CarouselResourceModel::TABLE_NAME);
        $table = $setup->getConnection()->newTable($tableName)
            ->addColumn(
                CarouselResourceModel::ID_FIELD_NAME,
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
                'title',
                Table::TYPE_TEXT,
                255,
                [
                    'nullable' => false
                ],
                'Title'
            )->addColumn(
                'sort_order',
                Table::TYPE_FLOAT,
                null,
                [
                    'nullable' => false,
                    'default' => 0
                ],
                'Sort Order'
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
            )->setComment("Codilar Carousel Table");
        $setup->getConnection()->createTable($table);
    }
}