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

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $cateTableName = $setup->getTable('bss_gallery_category');
        $itemTableName = $setup->getTable('bss_gallery_item');

        
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $connection = $setup->getConnection();
            if ($connection->isTableExists($cateTableName) == true) {
                $connection->modifyColumn(
                    $cateTableName,
                    'Item_ids',
                    ['type' => Table::TYPE_TEXT, 'length' => '2M', 'nullable' => true, 'default' => null],
                    'Item IDs'
                );
            }
            if ($connection->isTableExists($itemTableName) == true) {
                $connection->modifyColumn(
                    $itemTableName,
                    'category_ids',
                    ['type' => Table::TYPE_TEXT, 'length' => '2M', 'nullable' => true, 'default' => null],
                    'Category IDs'
                );
            } 
        }
        $setup->endSetup();
    }
}