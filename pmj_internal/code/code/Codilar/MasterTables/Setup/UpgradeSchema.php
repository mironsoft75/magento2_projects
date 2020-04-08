<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 16/5/19
 * Time: 1:22 PM
 */

namespace Codilar\MasterTables\Setup;


use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;

        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_stone_bom'),
                'stone_shape',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Stone Shape'
                ]
            );
        }
    }
}