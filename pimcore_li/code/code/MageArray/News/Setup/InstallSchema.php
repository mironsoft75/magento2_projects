<?php

namespace MageArray\News\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class InstallSchema
 * @package MageArray\News\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Zend_Db_Exception
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $installer = $setup;

        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magearray_news_post')
        )->addColumn(
            'newspost_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'News Post ID'
        )->addColumn(
            'title',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Title'
        )->addColumn(
            'url_key',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'URL key'
        )->addColumn(
            'content',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Content'
        )->addColumn(
            'short_content',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Short Content'
        )->addColumn(
            'image',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Featured Image'
        )->addColumn(
            'source_edition',
            Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Source edition'
        )->addColumn(
            'image_thumb',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Thumb Image'
        )->addColumn(
            'category',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'category'
        )->addColumn(
            'tags',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Tags'
        )->addColumn(
            'meta_title',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Meta Keywords'
        )->addColumn(
            'meta_keywords',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Meta Keywords'
        )->addColumn(
            'meta_description',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Meta Description'
        )->addColumn(
            'publish_date',
            Table::TYPE_DATE,
            null,
            [],
            'Publish Date'
        )->addColumn(
            'is_active',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Active Status'
        )->addColumn(
            'created_at',
            Table::TYPE_TIMESTAMP,
            null,
            [],
            'Creation Time'
        )->addColumn(
            'update_time',
            Table::TYPE_TIMESTAMP,
            null,
            [],
            'Modification Time'
        )->addColumn(
            'views',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Views'
        )->addColumn(
            'store_id',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Store ID'
        )
            ->setComment(
                'News Table'
            );

        $installer->getConnection()->createTable($table);

        $tableComment = $installer->getConnection()->newTable(
            $installer->getTable('magearray_news_comment')
        )->addColumn(
            'comment_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Comment ID'
        )->addColumn(
            'newspost_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'News Post ID'
        )->addColumn(
            'comment',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Comment'
        )->addColumn(
            'sender_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Name'
        )->addColumn(
            'sender_email',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Email'
        )->addColumn(
            'commented_date',
            Table::TYPE_DATE,
            null,
            ['nullable' => false],
            'Commented Date'
        )->addColumn(
            'comment_status',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Comment Status'
        )->addIndex(
            $installer->getIdxName('magearray_news_comments', ['newspost_id']),
            ['newspost_id']
        )->addForeignKey(
            $installer
                ->getFkName(
                    'magearray_news_comments',
                    'newspost_id',
                    'magearray_news_post',
                    'newspost_id'
                ),
            'newspost_id',
            $installer->getTable('magearray_news_post'),
            'newspost_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Comments Table'
        );

        $installer->getConnection()->createTable($tableComment);

        $tableCat = $installer->getConnection()->newTable(
            $installer->getTable('magearray_news_category')
        )->addColumn(
            'cat_id',
            Table::TYPE_INTEGER,
            10,
            ['identity' => true, 'nullable' => false, 'primary' => true],
            'Category ID'
        )->addColumn(
            'cat_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Category Name'
        )->addColumn(
            'cat_status',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Category Status'
        )->addColumn(
            'cat_parent',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Category Parent'
        )->addColumn(
            'cat_url_key',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Category URL key'
        )->addColumn(
            'cat_sort_order',
            Table::TYPE_SMALLINT,
            null,
            [],
            'Category Sort Order'
        )->addColumn(
            'cat_store_id',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Category Store ID'
        )->addColumn(
            'cat_meta_title',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Category Meta Title'
        )->addColumn(
            'cat_meta_keywords',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Category Meta Keywords'
        )->addColumn(
            'cat_meta_description',
            Table::TYPE_TEXT,
            '2M',
            ['nullable' => false],
            'Category Meta Description'
        )->setComment(
            'Category Table'
        );

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magearray_news_post_relatedproduct')
        )->addColumn(
            'newspost_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Post ID'
        )->addColumn(
            'related_id',
            Table::TYPE_INTEGER,
            null,
            ['unsigned' => true, 'nullable' => false, 'primary' => true],
            'Related Product ID'
        )->addColumn(
            'position',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Position'
        )->addIndex(
            $installer
                ->getIdxName(
                    'magearray_news_post_relatedproduct',
                    ['related_id']
                ),
            ['related_id']
        )->addForeignKey(
            $installer
                ->getFkName(
                    'magearray_news_post_relatedproduct',
                    'newspost_id',
                    'magearray_news_post',
                    'newspost_id'
                ),
            'newspost_id',
            $installer->getTable('magearray_news_post'),
            'newspost_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'magearray_news_post_relatedproduct',
                'related_id',
                'catalog_product_entity',
                'entity_id'
            ),
            'related_id',
            $installer->getTable('catalog_product_entity'),
            'entity_id',
            Table::ACTION_CASCADE
        )->setComment(
            'MageArray News Post To Product Relation Table'
        );
        $installer->getConnection()->createTable($table);

        $table = $installer->getConnection()->newTable(
            $installer->getTable('magearray_news_post_relatedpost')
        )->addColumn(
            'newspost_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Post ID'
        )->addColumn(
            'related_id',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false, 'primary' => true],
            'Related Post ID'
        )->addColumn(
            'position',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => false],
            'Position'
        )->addIndex(
            $installer
                ->getIdxName(
                    'magearray_news_post_relatedpost',
                    ['related_id']
                ),
            ['related_id']
        )->addForeignKey(
            $installer->getFkName(
                'magearray_news_post_relatedproduct1',
                'newspost_id',
                'magearray_news_post',
                'newspost_id'
            ),
            'newspost_id',
            $installer->getTable('magearray_news_post'),
            'newspost_id',
            Table::ACTION_CASCADE
        )->addForeignKey(
            $installer->getFkName(
                'magearray_news_post_relatedproduct2',
                'related_id',
                'magearray_news_post',
                'newspost_id'
            ),
            'newspost_id',
            $installer->getTable('magearray_news_post'),
            'newspost_id',
            Table::ACTION_CASCADE
        )->setComment(
            'Magearray News Post To Post Relation Table'
        );
        $installer->getConnection()->createTable($table);

        $installer->getConnection()->createTable($tableCat);
        $installer->endSetup();
    }
}
