<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/4/19
 * Time: 3:37 PM
 */

namespace Codilar\Appointment\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;

/**
 * Class UpgradeSchema
 * @package Codilar\Appointment\Setup
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

        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $table = $installer->getTable('codilar_appointment');
            $columns = [
                'request_url' => [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'nullable' => false,
                    'comment' => 'Request Url',
                ],

            ];

            $connection = $installer->getConnection();
            foreach ($columns as $name => $definition) {
                $connection->addColumn($table, $name, $definition);
            }

            $installer->endSetup();
        }
        if (version_compare($context->getVersion(), '1.0.2', '<')) {
            $installer->getConnection()->changeColumn(
                'codilar_appointment',
                'requested_time',
                'requested_time',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => ''
                ]
            );
        }
        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $installer->getConnection()->changeColumn(
                'codilar_appointment',
                'mobile_number',
                'mobile_number',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => false,
                    'default' => ''
                ]
            );
        }
    }
}