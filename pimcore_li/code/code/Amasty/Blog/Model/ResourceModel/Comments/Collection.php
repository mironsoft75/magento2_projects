<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model\ResourceModel\Comments;

class Collection extends \Amasty\Blog\Model\ResourceModel\Abstracts\Collection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'comment_id';

    protected $_storeIds = '';

    protected $_map = ['fields' => [
        'comment_id' => 'main_table.comment_id'
    ]];

    public function _construct()
    {
        $this->_init('Amasty\Blog\Model\Comments', 'Amasty\Blog\Model\ResourceModel\Comments');
    }

    public function addPosts()
    {
        $this->getSelect()
            ->joinLeft(
                ['posts' => $this->getTable('amasty_blog_posts')],
                'main_table.post_id = posts.post_id',
                ['posts.title as title']
            )
        ;
        return $this;
    }

    public function addTagFilter($tagId)
    {
        $this->getSelect()
            ->joinLeft(
                ['tags' => $this->getTable('amasty_blog_posts_tags')],
                'main_table.post_id = tags.post_id',
                []
            )
            ->where('tags.tag_id = ?', $tagId)
            ->group('main_table.post_id');
        ;

        return $this;
    }


    public function addPostStoreFilter($storeIds)
    {
        if (!is_array($storeIds)){
            $storeIds = array($storeIds);
        }

        $table = $this->getTable('amasty_blog_posts_store');
        $storeIds = "'".implode("','", $storeIds)."'";
        $this->getSelect()->joinInner(array('store'=>$table), "store.post_id = main_table.post_id AND store.store_id IN ({$storeIds})", array());
        return $this;
    }

    public function addActiveFilter($ownerSessionId = null)
    {
        if ($ownerSessionId){
            $activeStatus = \Amasty\Blog\Model\Comments::STATUS_APPROVED;
            $pendingStatus = \Amasty\Blog\Model\Comments::STATUS_PENDING;
            $this->getSelect()
                ->where(new \Zend_Db_Expr("(main_table.status = '{$activeStatus}') OR ((main_table.status = '{$pendingStatus}') AND (main_table.session_id = '$ownerSessionId'))"))
            ;

        } else {
            $this->addFieldToFilter('status', \Amasty\Blog\Model\Comments::STATUS_APPROVED);
        }
        return $this;
    }

    public function addPostFilter($postId)
    {
        $this->addFieldToFilter('post_id', $postId);
        return $this;
    }

    public function setDateOrder($dir = 'DESC')
    {
        $this->getSelect()
            ->order("main_table.created_at {$dir}");
        return $this;
    }

    public function setNotReplies()
    {
        $this->getSelect()
            ->where("main_table.reply_to IS NULL")
        ;

        return $this;
    }

    public function setReplyToFilter($commentId)
    {
        $this->getSelect()
            ->where("main_table.reply_to = ?", $commentId)
        ;
        return $this;
    }

    public function addStatusFilter($statusId)
    {
        $this->getSelect()
            ->where(new \Zend_Db_Expr("main_table.status = '{$statusId}'"))
        ;
        return $this;
    }

    public function addStoreFilter($storeIds)
    {
        $this->_storeIds = $storeIds;
        return $this;
    }

    protected function _applyStoreFilter($storeIds = null)
    {
        if ($storeIds){

            if (!is_array($storeIds)){
                $storeIds = array($storeIds);
            }

            $storeIds = "'".implode("','", $storeIds)."'";
            $this
                ->getSelect()
                ->where(new \Zend_Db_Expr("main_table.store_id IN ({$storeIds})"))
            ;
        }

        return $this;
    }
}