<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */
namespace Bss\Gallery\Setup;

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
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()
            ->newTable($installer->getTable('bss_gallery_category'))
            ->addColumn(
                'category_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Category ID'
            )
            ->addColumn('Item_ids', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'Item IDs')
            ->addColumn('url_key', Table::TYPE_TEXT, 100, ['nullable' => true, 'default' => null])
            ->addColumn('thumbnail', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false], 'Category Title')
            ->addColumn('item_thumb_id', Table::TYPE_TEXT, '2M', ['nullable' => false, 'default' => ''], 'Item Thumb Id')
            ->addColumn('category_meta_keywords', Table::TYPE_TEXT, '2M', ['nullable' => true, 'default' => ''], 'Category Meta Keywords')
            ->addColumn('category_meta_description', Table::TYPE_TEXT, '2M', ['nullable' => true, 'default' => ''], 'Category Meta Description')
            ->addColumn('item_layout', Table::TYPE_SMALLINT, null, ['nullable' => true, 'default' => '1'], 'Category View Items Layout')
            ->addColumn('category_description', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => ''], 'Category Description')
            ->addColumn('slider_auto_play', Table::TYPE_SMALLINT, null, ['nullable' => true, 'default' => '1'], 'Slide Auto Play')
            ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Category Active?')
            ->addColumn('create_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Create Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
            ->addIndex($installer->getIdxName('gallery_category', ['url_key']), ['url_key'])
            ->setComment('Bss Gallery Category');

        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()
            ->newTable($installer->getTable('bss_gallery_item'))
            ->addColumn(
                'item_id',
                Table::TYPE_SMALLINT,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true],
                'Item ID'
            )
            ->addColumn(
                'sorting',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => true, 'default' => null],
                'Sort Order'
            )
            ->addColumn('category_ids', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null], 'Category IDs')
            ->addColumn('type_id', Table::TYPE_SMALLINT, null, ['nullable' => false], 'Type ID')
            ->addColumn('image', Table::TYPE_TEXT, 255, ['nullable' => false, 'default' => null])
            ->addColumn('video', Table::TYPE_TEXT, 255, ['nullable' => true, 'default' => null])
            ->addColumn('title', Table::TYPE_TEXT, 255, ['nullable' => false], 'Item Title')
            ->addColumn('description', Table::TYPE_TEXT, '2M', [], 'Item Description')
            ->addColumn('is_active', Table::TYPE_SMALLINT, null, ['nullable' => false, 'default' => '1'], 'Is Item Active?')
            ->addColumn('create_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Create Time')
            ->addColumn('update_time', Table::TYPE_DATETIME, null, ['nullable' => false], 'Update Time')
            ->setComment('Bss Gallery Item');

        $installer->getConnection()->createTable($table);

        $installer->endSetup();
    }

}
