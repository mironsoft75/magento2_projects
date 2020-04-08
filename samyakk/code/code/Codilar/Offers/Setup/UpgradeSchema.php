<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * @codeCoverageIgnore
 */
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
        if (version_compare($context->getVersion(),'1.0.2') < 0) {
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_offers'),
                'show_title',
                [
                    'type' => Table::TYPE_INTEGER,
                    'length' => 10,
                    'nullable' => false,
                    'default' => 1,
                    "after" => "title",
                    'comment' => 'Show Title'
                ]
            );
        }

        if (version_compare($context->getVersion(),'1.0.3') < 0) {
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_offers'),
                'display_type',
                [
                    'type' => Table::TYPE_INTEGER,
                    'length' => 10,
                    'default' => 1,
                    "after" => "show_title",
                    'comment' => 'Display Type: 1 => Carousel, 2 => Grid'
                ]
            );
        }
        $installer->endSetup();
    }
}
