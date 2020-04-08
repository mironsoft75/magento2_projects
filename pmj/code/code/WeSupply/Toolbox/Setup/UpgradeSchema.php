<?php
namespace WeSupply\Toolbox\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package WeSupply\Toolbox\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
            
            $setup->getConnection()->addColumn(
                $setup->getTable('wesupply_orders'),
                'store_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                    'nullable' => true,
                    'unsigned' => true,
                    'comment' => 'Store Id'
                ]
            );

            $setup->getConnection()
                ->addIndex(
                    $setup->getTable('wesupply_orders'),
                    $setup->getIdxName('wesupply_orders', ['store_id']),
                    ['store_id']
                );
        }
    }
}
