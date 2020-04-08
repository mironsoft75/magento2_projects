<?php

namespace Magestore\Bannerslider\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Psr\Log\LoggerInterface;

/**
 * Class UpgradeSchema
 * @package Magestore\Bannerslider\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * UpgradeSchema constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(
        LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        if (version_compare($context->getVersion(), '1.7.2') < 0) {
            $installer->getConnection()->addColumn(
                $installer->getTable('magestore_bannerslider_banner'),
                'mobile_image',
                [
                    'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                    'length' => 255,
                    'nullable' => true,
                    'comment' => 'Mobile Image'
                ]
            );
        }
        $installer->endSetup();
    }

}