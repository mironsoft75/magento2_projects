<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Codilar\Newsletter\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\DB\Ddl\Table;

/**
 * Upgrade the Newsletter module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $table = $setup->getTable('newsletter_subscriber');
            $setup->getConnection()->addColumn(
                $table,
                'gender',
                [
                    'type' => Table::TYPE_TEXT,
                    'length' => 10,
                    'nullable' => true,
                    'comment' => 'Gender'
                ]
            );
        }
        $setup->endSetup();
    }
}
