<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '1.2.3', '<')) {
            $this->addIndexFields($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addIndexFields(SchemaSetupInterface $setup)
    {
        $installer = $setup;
        $connection = $installer->getConnection();
        $connection->addIndex(
            $installer->getTable('amasty_blog_posts'),
            $setup->getIdxName(
                $installer->getTable('amasty_blog_posts'),
                ['title', 'short_content', 'full_content'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['title', 'short_content', 'full_content'],
            AdapterInterface::INDEX_TYPE_FULLTEXT
        );

        $connection->addIndex(
            $installer->getTable('amasty_blog_categories'),
            $setup->getIdxName(
                $installer->getTable('amasty_blog_categories'),
                ['name'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['name'],
            AdapterInterface::INDEX_TYPE_FULLTEXT
        );

        $connection->addIndex(
            $installer->getTable('amasty_blog_tags'),
            $setup->getIdxName(
                $installer->getTable('amasty_blog_tags'),
                ['name'],
                AdapterInterface::INDEX_TYPE_FULLTEXT
            ),
            ['name'],
            AdapterInterface::INDEX_TYPE_FULLTEXT
        );
    }
}
