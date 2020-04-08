<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Comments;

class Form extends \Amasty\Blog\Block\Comments
{
    protected $_collection;

    /**
     * @var string
     */
    protected $_template = 'Amasty_Blog::comments/form.phtml';

    /**
     * @var string|null
     */
    protected $_post;

    /**
     * @var string|null
     */
    protected $_replyTo;

    /**
     * @var array
     */
    protected $_formData = [];

    /**
     * @var ConfigProvider
     */
    private $configProvider;

    public function __construct(
        \Amasty\Blog\Model\ConfigProvider $configProvider,
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
        parent::__construct(
            $context,
            $commentsModel,
            $settingsHelper,
            $dataHelper,
            $dateHelper,
            $objectManagerInterface,
            $encoderInterface,
            $encryptorInterface,
            $session,
            $registry,
            $data
        );
        $this->configProvider = $configProvider;
    }

    public function setPost($value)
    {
        $this->_post = $value;
        return $this;
    }

    public function setReplyTo($value)
    {
        $this->_replyTo = $value;
    }

    public function canPostComments()
    {
        return $this->settingsHelper->getCommentsAllowGuests();
    }

    public function getReplyTo()
    {
        return $this->_replyTo ? $this->_replyTo->getId() : 0;
    }

    public function getPost()
    {
        return $this->_post;
    }

    public function getPostId()
    {
        return $this->getPost()->getId();
    }

    public function isReply()
    {
        return !!$this->getReplyTo();
    }

    public function canPost()
    {
        return $this->settingsHelper->getCommentsAllowGuests() || $this->isLoggedId();
    }

    public function setFormData(array $data)
    {
        $this->_formData = $data;
    }

    public function getFormData()
    {
        return new \Magento\Framework\DataObject($this->_formData);
    }

    public function getRegisterUrl()
    {
        return $this->getUrl('customer/account/create');
    }

    public function getLoginUrl()
    {
        $params = array('post_id' => $this->getPostId());
        if ($this->isReply()){
            $params['reply_to'] = $this->getReplyTo();
        }
        return $this->getUrl('customer/account/login', $params);
    }

    public function isLoggedId()
    {
        return $this->session->isLoggedIn();
    }

    public function getCustomerId()
    {
        return $this->session->getCustomerId();
    }

    public function getCustomerName()
    {
        if ($this->isLoggedId()) {
            return $this->session->getCustomer()->getName();
        } else {
            return $this->dataHelper->loadCommentorName();
        }
    }

    public function getCustomerEmail()
    {
        if ($this->isLoggedId()) {
            return $this->session->getCustomer()->getEmail();
        } else {
            return $this->dataHelper->loadCommentorEmail();
        }
    }

    public function getSessionId()
    {
        return $this->getData('session_id');
    }

    public function getMessageBlockHtml()
    {
        $block = $this->getMessagesBlock();
        if ($block) {
            $block->setMessages($this->session->getMessages(true));
        }
        return $block->toHtml();
    }

    public function getEmailsEnabled()
    {
        return $this->settingsHelper->getCommentNotificationsEnabled();
    }

    public function getReplyToCustomerName()
    {
        $comment = $this->commentsModel->load($this->getReplyTo());
        if ($comment->getId()) {
            return $comment->getName();
        }

        return false;
    }

    public function isCustomerSubscribed()
    {
        $storedValue = $this->dataHelper->loadIsSubscribed();
        if ($storedValue !== null) {
            return $storedValue;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isGdprEnabled()
    {
        return (bool)$this->configProvider->isShowGdpr();
    }

    /**
     * @return string
     */
    public function getGdprText()
    {
        return $this->configProvider->getGdprText();
    }

    /**
     * @return bool
     */
    public function isAskEmail()
    {
        return $this->configProvider->isAskEmail();
    }

    /**
     * @return bool
     */
    public function isAskName()
    {
        return $this->configProvider->isAskName();
    }
}
