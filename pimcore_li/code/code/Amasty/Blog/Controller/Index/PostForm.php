<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Index;

use Amasty\Blog\Helper\Settings;
use Amasty\Blog\Helper\Strings;
use Amasty\Blog\Model\Comments;
use Amasty\Blog\Model\Posts;
use Magento\Framework\App\Action;

class PostForm extends \Magento\Framework\App\Action\Action
{

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;
    /**
     * @var \Magento\Framework\Url\DecoderInterface
     */
    private $decoderInterface;
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    private $encryptorInterface;
    /**
     * @var Posts
     */
    private $postsModel;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var Settings
     */
    private $settingsHelper;
    /**
     * @var Comments
     */
    private $commentsModel;
    /**
     * @var Action\Context
     */
    private $context;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Url\DecoderInterface $decoderInterface,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Framework\Registry $registry,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Amasty\Blog\Model\Posts $postsModel,
        \Amasty\Blog\Model\Comments $commentsModel,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
    ) {
        parent::__construct($context);
        $this->storeManagerInterface = $storeManagerInterface;
        $this->decoderInterface = $decoderInterface;
        $this->encryptorInterface = $encryptorInterface;
        $this->postsModel = $postsModel;
        $this->registry = $registry;
        $this->customerSession = $customerSession;
        $this->settingsHelper = $settingsHelper;
        $this->commentsModel = $commentsModel;
        $this->context = $context;
    }

    public function execute()
    {
        $result = array();
        $error = false;

        $postData = $this->getRequest()->getPost()->toArray();

        $postData['store_id'] = $this->storeManagerInterface->getStore()->getId();

        $post = new \Magento\Framework\DataObject($postData);

        $replyTo = $post->getReplyTo();
        $postId = $this->getRequest()->getParam('post_id');

        if ($postId) {
            $postId = $this->encryptorInterface->decrypt($this->decoderInterface->decode($postId));

            $postInstance = $this->postsModel->load($postId);
            if ($postInstance->getId()) {
                $this->registry->register('current_post', $postInstance);
                
                $post
                    ->setPostId($postId)
                    ->setNotified('0')
                ;
                if ($this->customerSession->getCustomer()->getEntityId() || $this->settingsHelper->getCommentsAllowGuests()) {

                    # Save Subscription
                    $sessionId = $post->getSessionId();

                    # Subscription logic start
                    //TODO: subscriptional functionality
                    if (0==1 && $this->settingsHelper->getCommentNotificationsEnabled()) {
                        $isSubscribed = $post->getData('subscribe_to_replies');

                        $subscription = 1;
                        $subscription->loadByEmail($postId, $post->getEmail());

                        if ($subscription->getId()) {

                            if ($this->_getCustomerSession()->isLoggedIn()) {
                                if ($subscription->getCustomerId() != $this->_getCustomerSession()->getCustomerId()) {
                                    $subscription->setCustomerId($this->_getCustomerSession()->getCustomerId());
                                }
                            } else {
                                $subscription->setSessionId($sessionId);
                            }

                            $subscription
                                ->setStatus($subscription->mapCheckbox($isSubscribed))
                                ->save()
                            ;

                        } else {

                            $subscription
                                ->setPostId($postId)
                                ->setCustomerName($post->getName())
                                ->setEmail($post->getEmail())
                                ->setStatus($subscription->mapCheckbox($isSubscribed))
                                ->setStoreId(1)
                                ->generateHash()
                            ;

                            if ($this->_getCustomerSession()->isLoggedIn()) {
                                $subscription->setCustomerId($this->_getCustomerSession()->getCustomerId());
                            } else {
                                $subscription->setSessionId($sessionId);
                            }

                            $subscription->save();
                        }
                    }
                    # Subscription logic end

                    /*
                    # Save commenter details
                        $this->_helper()
                            ->saveCommentorName($post->getName())
                            ->saveCommentorEmail($post->getEmail())
                            ->saveIsSubscribed($isSubscribed)
                        ;
                    */
                    $newComment = null;

                    if ($replyTo) {
                        $comment = $this->commentsModel->load($replyTo);
                        if ($comment->getId()) {
                            $post->setNewComment('yes');
                            $newComment = $comment->reply($post->getData());
                        }
                    } else {
                        $post->unsetData('reply_to');
                        $newComment = $this->commentsModel->comment($post->getData());
                    }

                    if ($newComment) {
                        $message = $this->_view->getLayout()->createBlock('Amasty\Blog\Block\Comments\Message');
                        if ($message) {
                            $message->setMessage($newComment);
                            $message->setIsAjax(true);
                            $result['message'] = $message->toHtml();
                            $result['comment_id'] = $newComment->getId();
                            $result['count_code'] = $message->getCountCode();
                        }
                    } else {
                        $error = 1;
                        $this->context->getMessageManager()->addError(__("Can not create comment."));
                    }

                } else {
                    $error = 1;
                    $this->context->getMessageManager()->addError(__("Your session was expired. Please refresh this page and try again."));
                }

            } else {
                $this->context->getMessageManager()->addError(__("Post is not found."));
                $error = 1;
            }
        }

        if ($error) {
            $result['error'] = 1;
        }

        $this->_ajaxResponse($result);
    }

    protected function _ajaxResponse($result = array())
    {
        $this->getResponse()
            ->setHeader('Content-Type', 'application/json')
            ->setBody(\Zend_Json::encode($result))
        ;
    }
}
