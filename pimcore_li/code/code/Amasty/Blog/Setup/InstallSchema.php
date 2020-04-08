<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $this->createCategories($installer);
        $this->createCategoriesStores($installer);
        $this->createPosts($installer);
        $this->createTags($installer);
        $this->createPostsTag($installer);
        $this->createPostsStores($installer);
        $this->createPostsCategory($installer);
        $this->createComments($installer);
        $this->createViews($installer);
        $installer->endSetup();
    }

    protected function createComments($installer)
    {

        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_comments'))
            ->addColumn(
                'comment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                20,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Comment Id'
            )
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => false],
                'Post Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => false],
                'Store Id'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['unsigned' => true, 'nullable' => false, 'primary' => false],
                'Status'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'primary' => false, 'default' => null],
                'Customer Id'
            )
            ->addColumn(
                'reply_to',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                20,
                ['unsigned' => true, 'primary' => false, 'default' => null],
                'Reply To'
            )
            ->addColumn(
                'message',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Message'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Name'
            )
            ->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Email'
            )
            ->addColumn(
                'session_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Session Id'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )

            ->addForeignKey(
                $installer->getFkName('amasty_blog_comments', 'post_id', 'amasty_blog_posts', 'post_id'),
                'post_id',
                $installer->getTable('amasty_blog_posts'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_comments', 'reply_to', 'amasty_blog_comments', 'post_id'),
                'reply_to',
                $installer->getTable('amasty_blog_comments'),
                'comment_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
        ;
        $installer->getConnection()->createTable($table);
    }

    protected function createDrafts($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_drafts'))
            ->addColumn(
                'draft_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Draft Id'
            )
            ->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => false, 'primary' => false],
                'User Id'
            )
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true, 'nullable' => true, 'primary' => false, 'default' => 0],
                'Post Id'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
                null,
                ['nullable' => false],
                'Updated At'
            )
            ->addColumn(
                'full_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Full Content'
            )
            ->addColumn(
                'short_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Short Content'
            )
            ->addIndex(
                $installer->getIdxName('amasty_blog_drafts', ['draft_id']),
                ['draft_id']
            )->addForeignKey(
                $installer->getFkName('amasty_blog_drafts', 'post_id', 'mp_blog_posts', 'post_id'),
                'customer_id',
                $installer->getTable('amasty_blog_drafts'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
        ;
        $installer->getConnection()->createTable($table);
    }

    public function createAuthors($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_authors'))
            ->addColumn(
                'author_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Author Id'
            )
            ->addColumn(
                'user_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'User Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Name'
            )
            ->addColumn(
                'google_profile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Google Profile'
            )
            ->addColumn(
                'facebook_profile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Facebook Profile'
            )
            ->addColumn(
                'twitter_profile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Twitter Profile'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                null,
                ['unsigned' => true, 'nullable' => false, 'default' => 0],
                'Store Id'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => '0000-00-00 00:00:00'],
                'Updated At'
            );
        $installer->getConnection()->createTable($table);
    }

    public function createCategories($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_categories'))
            ->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Category Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Name'
            )
            ->addColumn(
                'url_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Url Key'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'unsigned' => true],
                'Status'
            )
            ->addColumn(
                'sort_order',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                5,
                ['nullable' => false, 'unsigned' => true, 'default' => 0],
                'Sort Order'
            )
            ->addColumn(
                'meta_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Meta Title'
            )
            ->addColumn(
                'meta_tags',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Meta Tags'
            )
            ->addColumn(
                'meta_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Meta Description'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            );

        $installer->getConnection()->createTable($table);
    }



    public function createCategoriesStores($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_categories_store'))
            ->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Category Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Store Id'
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_categories_store', 'category_id', 'amasty_blog_categories', 'category_id'),
                'category_id',
                $installer->getTable('amasty_blog_categories'),
                'category_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
        ;
        $installer->getConnection()->createTable($table);
    }

    public function createNotifications($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_comments_notifications'))
            ->addColumn(
                'notification_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Notification Id'
            )
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false, 'unsigned' => true],
                'Post Id'
            )
            ->addColumn(
                'subscription_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false, 'unsigned' => true],
                'Subscription Id'
            )
            ->addColumn(
                'comment_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
                20,
                ['nullable' => false, 'unsigned' => true],
                'Comment Id'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Status'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'unsigned' => true],
                'Status'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => '0000-00-00 00:00:00'],
                'Updated At'
            )
            ;
        $installer->getConnection()->createTable($table);
    }

    public function createSubscriptions($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_comments_subscriptions'))
            ->addColumn(
                'subscription_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Subscription Id'
            )
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => false, 'unsigned' => true],
                'Post Id'
            )
            ->addColumn(
                'customer_name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Customer Name'
            )
            ->addColumn(
                'email',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Email'
            )
            ->addColumn(
                'session_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Session Id'
            )
            ->addColumn(
                'customer_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Customer Id'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'unsigned' => true],
                'Status'
            )
            ->addColumn(
                'hash',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Hash'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'unsigned' => true],
                'Status'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => '0000-00-00 00:00:00'],
                'Updated At'
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_comments_subscriptions', 'post_id', 'mp_blog_posts', 'post_id'),
                'post_id',
                $installer->getTable('amasty_blog_comments_subscriptions'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
        ;

        $installer->getConnection()->createTable($table);
    }

    public function createPosts($installer)
    {

        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_posts'))
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Subscription Id'
            )
            ->addColumn(
                'status',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                5,
                ['nullable' => false, 'unsigned' => true],
                'Status'
            )
            ->addColumn(
                'title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Title'
            )
            ->addColumn(
                'url_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => false],
                'Url Key'
            )
            ->addColumn(
                'use_comments',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Use Comments'
            )
            ->addColumn(
                'short_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Short Content'
            )
            ->addColumn(
                'full_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Full Content'
            )
            ->addColumn(
                'posted_by',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['nullable' => false],
                'Posted By'
            )
            ->addColumn(
                'google_profile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Google Profile'
            )
            ->addColumn(
                'facebook_profile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Facebook Profile'
            )
            ->addColumn(
                'twitter_profile',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Twitter Profile'
            )
            ->addColumn(
                'meta_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Meta Title'
            )
            ->addColumn(
                'meta_tags',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Meta Tags'
            )
            ->addColumn(
                'meta_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Meta Description'
            )
            ->addColumn(
                'created_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
                'Created At'
            )
            ->addColumn(
                'updated_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
                'Updated At'
            )
            ->addColumn(
                'published_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false],
                'Published At'
            )
            ->addColumn(
                'recently_commented_at',
                \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false, 'default' => '0000-00-00 00:00:00'],
                'Recently Commented At'
            )
            ->addColumn(
                'user_define_publish',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 0],
                'User Define Publish'
            )
            ->addColumn(
                'notify_on_enable',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 0],
                'Notify On Enable'
            )
            ->addColumn(
                'display_short_content',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 1],
                'Display Short Content'
            )
            ->addColumn(
                'comments_enabled',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                1,
                ['nullable' => false, 'default' => 1],
                'Comments Enabled'
            )
            ->addColumn(
                'views',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => false, 'unsigned' => true],
                'Views'
            )
            ->addColumn(
                'post_thumbnail',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Post Thumbnail'
            )
            ->addColumn(
                'list_thumbnail',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'List Thumbnail'
            )
            ->addColumn(
                'thumbnail_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Thumbnail Url'
            )
            ->addColumn(
                'grid_class',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                2,
                ['default' => 'w1', 'nullable' => false],
                'Grid Class'
            )
            ->addColumn(
                'canonical_url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                ['default' => null],
                'Canonical Url'
            )
        ;

        $installer->getConnection()->createTable($table);
    }

    public function createPostsCategory($installer)
    {
        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_posts_category'))
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Post Id'
            )
            ->addColumn(
                'category_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Category Id'
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_posts_category', 'post_id', 'amasty_blog_posts', 'post_id'),
                'post_id',
                $installer->getTable('amasty_blog_posts'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_posts_category', 'category_id', 'amasty_blog_categories', 'category_id'),
                'category_id',
                $installer->getTable('amasty_blog_categories'),
                'category_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ;
        $installer->getConnection()->createTable($table);
    }

    public function createPostsStores($installer)
    {

        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_posts_store'))
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Post Id'
            )
            ->addColumn(
                'store_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Store Id'
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_posts_store', 'post_id', 'amasty_blog_posts', 'post_id'),
                'post_id',
                $installer->getTable('amasty_blog_posts'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )

        ;
        $installer->getConnection()->createTable($table);
    }

    public function createPostsTag($installer)
    {

        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_posts_tag'))
            ->addColumn(
                'post_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Post Id'
            )
            ->addColumn(
                'tag_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['unsigned' => true],
                'Tag Id'
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_posts_tag', 'post_id', 'amasty_blog_posts', 'post_id'),
                'post_id',
                $installer->getTable('amasty_blog_posts'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )
            ->addForeignKey(
                $installer->getFkName('amasty_blog_posts_tag', 'post_id', 'amasty_blog_tags', 'tag_id'),
                'post_id',
                $installer->getTable('amasty_blog_posts'),
                'post_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
            )

        ;
        $installer->getConnection()->createTable($table);
    }

    public function createTags($installer)
    {

        $table = $installer->getConnection()
            ->newTable($installer->getTable('amasty_blog_tags'))
            ->addColumn(
                'tag_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
                'Tag Id'
            )
            ->addColumn(
                'name',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null, 'unique' => true],
                'Name'
            )
            ->addColumn(
                'url_key',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Url Key'
            )
            ->addColumn(
                'meta_title',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Meta Title'
            )
            ->addColumn(
                'meta_tags',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['default' => null],
                'Meta Tags'
            )
            ->addColumn(
                'meta_description',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                null,
                [],
                'Meta Description'
            )


        ;
        $installer->getConnection()->createTable($table);
    }


    protected function createViews($installer)
    {
        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_blog_views')
        )->addColumn(
            'view_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            20,
            ['unsigned' => true, 'nullable' => false, 'identity' => true, 'primary' => true],
            'View Id'
        )->addColumn(
            'post_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false],
            'Post Id'
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true],
            'Customer Id'
        )->addColumn(
            'session_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => false],
            'Session Id'
        )->addColumn(
            'remote_addr',
            \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
            20,
            ['nullable' => false],
            'Remote Address'
        )->addColumn(
            'store_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            5,
            ['nullable' => false, 'unsigned' => true],
            'Store Id'
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => 'CURRENT_TIMESTAMP'],
            'Created At'
        )->addColumn(
            'referer_url',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['nullable' => true],
            'Referer Url'
        )
        ->addForeignKey(
            $installer->getFkName('amasty_blog_views', 'post_id', 'amasty_blog_posts', 'post_id'),
            'post_id',
            $installer->getTable('amasty_blog_posts'),
            'post_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        )
        ;
        $installer->getConnection()->createTable($table);
    }


    protected function createamasty_test22($installer)
    {
        $table = $installer->getConnection()->newTable(
            $installer->getTable('amasty_test22')
        )->addColumn(
            'purchased_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'identity' => true, 'primary' => true]
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'default' => '0']
        )->addColumn(
            'order_increment_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['default' => null]
        )->addColumn(
            'order_item_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'nullable' => false, 'default' => '0']
        )->addColumn(
            'created_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => 'CURRENT_TIMESTAMP']
        )->addColumn(
            'updated_at',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            null,
            ['nullable' => false, 'default' => 'CURRENT_TIMESTAMP']
        )->addColumn(
            'customer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            10,
            ['unsigned' => true, 'default' => '0']
        )->addColumn(
            'product_name',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => null]
        )->addColumn(
            'product_sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => null]
        )->addColumn(
            'link_section_title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            ['default' => null]
        )->addIndex(
            $installer->getIdxName('amasty_test22', ['order_id']),
            ['order_id']
        )->addIndex(
            $installer->getIdxName('amasty_test22', ['order_item_id']),
            ['order_item_id']
        )->addIndex(
            $installer->getIdxName('amasty_test22', ['customer_id']),
            ['customer_id']
        )->addForeignKey(
            $installer->getFkName('amasty_test22', 'customer_id', 'customer_entity', 'entity_id'),
            'customer_id',
            $installer->getTable('customer_entity'),
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::ACTION_SET_null
        )
            ->addForeignKey(
                $installer->getFkName('amasty_test22', 'order_id', 'sales_order', 'entity_id'),
                'order_id',
                $installer->getTable('sales_order'),
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::ACTION_SET_null
            )
        ;
        $installer->getConnection()->createTable($table);
    }

}
