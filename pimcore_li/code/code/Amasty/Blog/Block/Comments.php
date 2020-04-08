<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block;
use Amasty\Blog\Helper\Data;
use Amasty\Blog\Helper\Settings;

class Comments extends \Magento\Framework\View\Element\Template
{
    protected $_commentCollection;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;
    /**
     * @var \Amasty\Blog\Model\Comments
     */
    protected $commentsModel;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $session;
    /**
     * @var Settings
     */
    protected $settingsHelper;
    /**
     * @var \Magento\Framework\Url\EncoderInterface
     */
    protected $encoderInterface;
    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $encryptorInterface;
    /**
     * @var Data
     */
    protected $dataHelper;
    /**
     * @var \Amasty\Blog\Helper\Date
     */
    protected $dateHelper;
    /**
     * @var \Magento\Framework\View\Element\Template\Context
     */
    protected $context;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManagerInterface;

    /**
     * Comments constructor.
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Amasty\Blog\Model\Comments $commentsModel
     * @param Settings $settingsHelper
     * @param \Magento\Framework\Url\EncoderInterface $encoderInterface
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface
     * @param \Magento\Customer\Model\Session $session
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Model\Comments $commentsModel,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        \Amasty\Blog\Helper\Data $dataHelper,
        \Amasty\Blog\Helper\Date $dateHelper,
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        \Magento\Framework\Url\EncoderInterface $encoderInterface,
        \Magento\Framework\Encryption\EncryptorInterface $encryptorInterface,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\Registry $registry,
        array $data =[]
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->commentsModel = $commentsModel;
        $this->session = $session;
        $this->settingsHelper = $settingsHelper;
        $this->encoderInterface = $encoderInterface;
        $this->encryptorInterface = $encryptorInterface;
        $this->dataHelper = $dataHelper;
        $this->dateHelper = $dateHelper;
        $this->context = $context;
        $this->objectManagerInterface = $objectManagerInterface;
    }

    public function getPost()
    {
        $parent = $this->getParentBlock();
        if ($parent){
            if ($parent instanceof \Amasty\Blog\Block\Content\Post){
                return $parent->getPost();
            }
        } else {

            return $this->registry->registry('current_post');
        }
        return false;
    }

    public function getCommentsCount()
    {
        $comments = $this->commentsModel->getCollection();

        if (!$this->_storeManager->isSingleStoreMode()){
            $comments->addStoreFilter($this->_storeManager->getStore()->getId());
        }

        $comments
            ->addPostFilter($this->getPost()->getId())
            ->addActiveFilter(
                $this->settingsHelper->getCommentsAutoapprove() ?
                null :
                $this->session->getSessionId()
            )
        ;

        return $comments->getSize();
    }

    public function getCollection()
    {

        $commentsModel = $this->objectManagerInterface->create('Amasty\Blog\Model\Comments');
        $comments = $commentsModel->getCollection();

        if (!$this->_storeManager->isSingleStoreMode()){
            $comments->addStoreFilter($this->_storeManager->getStore()->getId());
        }

        $comments
            ->addPostFilter($this->getPost()->getId())
            ->addActiveFilter(
                $this->settingsHelper->getCommentsAutoapprove() ?
                null :
                $this->session->getSessionId()
            )
        ;

        $comments
            ->setDateOrder(\Magento\Framework\DB\Select::SQL_ASC)
            ->setNotReplies()
            ;

        $this->_commentCollection = $comments;

        return $this->_commentCollection;
    }

    public function getMessageHtml(\Amasty\Blog\Model\Comments $message)
    {
        $messageBlock = $this->getLayout()->createBlock('Amasty\Blog\Block\Comments\Message');
        if ($messageBlock){
            $messageBlock->setMessage($message);
            return $messageBlock->toHtml();
        }
        return false;
    }

    public function getFormUrl()
    {
        return $this->getUrl('amblog/index/form', array(
                                                        'reply_to'=>'{{reply_to}}',
                                                        'post_id'=>'{{post_id}}',
                                                        'session_id'=>'{{session_id}}',
                                                    ));
    }

    public function getPostId()
    {
        return $this->encoderInterface->encode($this->encryptorInterface->encrypt($this->getPost()->getId()));
    }

    public function getPostUrl()
    {
        return $this->getUrl('amblog/index/postForm', array(
            'reply_to'=>'{{reply_to}}',
            'post_id'=>'{{post_id}}',
        ));
    }

    public function showRss()
    {
        return $this->settingsHelper->getRssComment();
    }

    public function getRssCommentFeedUrl()
    {
        $params = array(
            "post_id" => $this->getPost()->getId(),
        );

        if (!$this->_storeManager->isSingleStoreMode()){
            $params['store_id'] = $this->_storeManager->getStore()->getId();
        }
        return $this->getUrl('amblog/index/rss/comment', $params);
    }

    public function getColorClass()
    {
        return $this->settingsHelper->getIconColorClass();
    }

    public function commentsEnabled()
    {
        return $this->settingsHelper->getUseComments();
    }
}