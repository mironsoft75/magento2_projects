<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Comments;

class Message extends \Amasty\Blog\Block\Comments
{
    protected $_collection;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::comments/list/message.phtml");
    }

    public function getMessage()
    {
        return $this->getData('message');
    }

    public function getContent()
    {
        return $this->getMessage()->getMessage();
    }

    public function getMessageId()
    {
        return $this->getMessage()->getId();
    }

    public function getAuthor()
    {
        return $this->getMessage()->getName();
    }

    public function getDate()
    {
        return $this->dateHelper->renderDate($this->getMessage()->getCreatedAt());
    }

    public function getTime()
    {
        return $this->dateHelper->renderTime($this->getMessage()->getCreatedAt());
    }

    public function getRepliesCollection()
    {
        $commentsModel = $this->objectManagerInterface->create('Amasty\Blog\Model\Comments');
        $comments = $commentsModel->getCollection();

        if (!$this->_storeManager->isSingleStoreMode()){
            $comments->addStoreFilter($this->_storeManager->getStore()->getId());
        }

        $comments
            ->addActiveFilter($this->settingsHelper->getCommentsAutoapprove() ? null : $this->session->getSessionId() )
        ;

        $comments
            ->setDateOrder(\Magento\Framework\DB\Select::SQL_ASC)
            ->setReplyToFilter($this->getMessage()->getId())
        ;

        $this->_collection = $comments;
        return $this->_collection;
    }

    /**
     * Replies Html
     *
     * @return string
     */
    public function getRepliesHtml()
    {
        $html = "";
        foreach ($this->getRepliesCollection() as $message){
            $messageBlock = $this->getLayout()->createBlock('Amasty\Blog\Block\Comments\Message');
            if ($messageBlock){
                $messageBlock->setMessage($message);
                $html .= $messageBlock->toHtml();
            }
        }
        return $html;
    }

    public function isReply()
    {
        if ($this->getIsAjax()){
            return false;
        }
        if ($this->getMessage()->getReplyTo()){
            $flag = 'amblog_reply_'.$this->getMessage()->getReplyTo();
            if (!$this->registry->registry($flag)){
                $this->registry->register($flag, true);
                return true;
            }
        }
        return false;
    }

    public function getCountCode()
    {
        return $this->getCommentsCount() ? __("%1 comments", $this->getCommentsCount()) : __("No comments");
    }

    public function getNeedApproveMessage()
    {
        return ($this->getMessage()->getStatus() == \Amasty\Blog\Model\Comments::STATUS_PENDING);
    }

    public function isMyComment()
    {
        if ($this->getMessage()){

            $message = $this->getMessage();
            if ($this->session->isLoggedIn()){
                $result = $this->session->getCustomerId() == $message->getCustomerId();
            } else {
                $result = $this->session->getSessionId() == $message->getSessionId();
            }

            return $this->getIsAjax() || $result;
        }
    }
}