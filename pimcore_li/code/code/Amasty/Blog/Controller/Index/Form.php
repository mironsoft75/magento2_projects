<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Index;

use Magento\Framework\App\Action;

class Form extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Amasty\Blog\Model\Posts
     */
    private $postsModel;
    /**
     * @var \Amasty\Blog\Model\Comments
     */
    private $commentsModel;

    /**
     * Form constructor.
     * @param Action\Context $context
     * @param \Amasty\Blog\Model\Posts $postsModel
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Amasty\Blog\Model\Posts $postsModel,
        \Amasty\Blog\Model\Comments $commentsModel
    ) {
        parent::__construct($context);
        $this->postsModel = $postsModel;
        $this->commentsModel = $commentsModel;
    }

    public function execute()
    {
        //$this->_view->loadLayout();

        

        $result = array();
        $error = false;

        $postId = $this->getRequest()->getParam('post_id');
        $sessionId = $this->getRequest()->getParam('session_id');
        if ($postId) {
            $postId = 1;// $this->_core()->decrypt( $this->_core()->urlDecode($postId));

            $post = $this->postsModel->load($postId);
            if ($postId) {
                $replyTo = $this->getRequest()->getParam('reply_to');

                if (!is_null($replyTo)) {
                    $comment = $this->commentsModel->load($replyTo);
                }

                $form = $this->_view->getLayout()->createBlock('Amasty\Blog\Block\Comments\Form');//$this->_view->getLayout()->getBlock('amblog.form');
                if ($form) {

                    $form->setPost($post)->setSessionId($sessionId);
                    if ($comment->getId()) {
                        $form->setReplyTo($comment);
                    }

                    //$form->setSecureCode($this->_helper()->_secure()->getSecureCode($postId, $replyTo));
                    $result['form'] = $form->toHtml();
                }

            } else {
                //$this->_getCustomerSession()->addError($this->_helper()->__("Post is not found."));
                $error = true;
            }
        }

        if ($error) {
            $result['error'] = 1;
            $result['message'] = $this->_getMessageBlockHtml();
        }
        $this->_ajaxResponse($result);
    }

    protected function _getMessageBlockHtml()
    {
//        return $this->_view->getLayout()->getMessagesBlock()->addMessages('123')->toHtml();
    }

    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()
            ->setHeader('Content-Type', 'application/json')
            ->setBody(\Zend_Json::encode($result))
        ;
    }

}
