<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Model;

class Comments extends AbstractModel
{
    const STATUS_PENDING = 1;
    const STATUS_APPROVED = 2;
    const STATUS_REJECTED = 3;

    protected $_post;

    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Blog\Model\ResourceModel\Comments');
    }

    public function getPost()
    {
        if (!$this->_post){
            $post = $this->objectManagerInterface->create('Amasty\Blog\Model\Posts')->load($this->getPostId());
            $post->setStoreId($this->getStoreId());

            $this->_post = $post;
        }
        return $this->_post;
    }

    public function getPostTitle()
    {
        return $this->getPost() ? $this->getPost()->getTitle() : '';
    }

    public function getCommentUrl()
    {
        return $this
            ->urlHelper
            ->setStoreId(
                $this->getStoreId()
            )->getUrl(
                $this->getPost()->getId()
            )
        ."#am-blog-comment-"
        .$this->getId()
            ;
    }

    public function comment(array $data)
    {
        $this->addData($data);
        $this->setStoreId($this->storeManagerInterface->getStore()->getId());
        if ($this->settings->getCommentsAutoapprove()){
            $this->setStatus(self::STATUS_APPROVED);
            $this->setSessionId(null);
        } else {
            $this->setStatus(self::STATUS_PENDING);
        }
        if ($this->getNewComment() == 'yes') {
            $this->setId(NULL);
        }
        $this->setMessage( $this->_prepareComment($data['message']) );
        $this->save();
        return $this;
    }

    public function reply(array $data)
    {
        $this->setReplyTo($this->getId());
        $this->comment($data);
        return $this;
    }

    public function activate()
    {
        $this->setStatus(self::STATUS_APPROVED);
        $this->save();
        return $this;
    }

    public function inactivate()
    {
        $this->setStatus(self::STATUS_REJECTED);
        $this->save();
        return $this;
    }

    protected function _prepareComment($message)
    {
        $message = html_entity_decode($message);
        $message = strip_tags($message);
        $message = trim($message);
        return $message;
    }

}