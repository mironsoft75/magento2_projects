<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 28/11/18
 * Time: 7:16 PM
 */

namespace Codilar\Videostore\Setup;


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

        if (version_compare($context->getVersion(), '1.0.7', '<')) {
            $installer->getConnection()->changeColumn(
                'codilar_videostore_cart',
                'videostore_customer_session_id',
                'videostore_customer_session_id',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => ''
                ]
            );
            $installer->getConnection()->changeColumn(
                'codilar_videostore_request',
                'mobile_number',
                'mobile_number',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => false,
                    'default' => ''
                ]
            );
            $installer->getConnection()->changeColumn(
                'codilar_videostore_request',
                'videostore_request_created_at',
                'videostore_request_created_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => '',
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT
                ]
            );
            $installer->getConnection()->changeColumn(
                'codilar_videostore_request',
                'videostore_request_updated_at',
                'videostore_request_updated_at',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                    'length' => '',
                    'nullable' => false,
                    'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE
                ]
            );
            $installer->getConnection()->changeColumn(
                'codilar_videostore_request',
                'requested_time',
                'requested_time',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => false,
                    'default' => ''
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_videostore_request'),
                'country',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Country'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_videostore_request'),
                'state',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'State'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_videostore_request'),
                'pending_flag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Pending Flag'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_videostore_request'),
                'processing_flag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Processing Flag'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_videostore_request'),
                'accept_flag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Accept Flag'
                ]
            );
            $installer->getConnection()->addColumn(
                $installer->getTable('codilar_videostore_request'),
                'reject_flag',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => '',
                    'nullable' => true,
                    'default' => '',
                    'comment' => 'Reject Flag'
                ]
            );
        }
    }

}