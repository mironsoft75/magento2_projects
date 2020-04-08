<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const KEY_CUSTOMER_NAME = 'amblog-customer-name';
    const KEY_CUSTOMER_EMAIL = 'amblog-customer-email';
    const KEY_IS_SUBSCRIBED = 'amblog-is-subscribed';

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var int
     */
    protected $_statusId = null;
    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    private $backendUrl;
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;
    /**
     * @var \Amasty\Blog\Model\Blog\Config
     */
    private $blogConfig;
    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    private $viewInterface;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $session;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\ViewInterface $viewInterface,
        \Amasty\Blog\Model\Blog\Config $blogConfig,
        \Magento\Customer\Model\Session $session,
        \Magento\Framework\App\Helper\Context $context
    ){
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->backendUrl = $backendUrl;
        $this->registry = $registry;
        $this->blogConfig = $blogConfig;
        $this->viewInterface = $viewInterface;
        $this->session = $session;
    }


    /**
     * @param $name
     *
     * @return string
     */
    public function getImageUrl($name)
    {
        $path = $this->_storeManager->getStore()->getBaseUrl( \Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $path . 'amasty/blog/'. $name;

    }

    public function getProductsGridUrl()
    {
        return $this->backendUrl->getUrl('amasty_blog/tags/posts', ['_current' => true]);
    }
    
    public function getSocialNetworks()
    {
        return explode(",", $this->scopeConfig->getValue(
            'amblog/social/networks',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        ) );
    }

    public function getHeaderHtml($post = null)
    {
        $details = $this->viewInterface->getLayout()->createBlock('Amasty\Blog\Block\Content\Post\Details');
        if ($details) {
            $details
                ->setPost($post)
                ->setTemplate("Amasty_Blog::post/header.phtml");;
            return $details->toHtml();
        }

        return false;
    }

    public function getFooterHtml($post = null)
    {
        $details = $this->viewInterface->getLayout()->createBlock('Amasty\Blog\Block\Content\Post\Details');
        if ($details) {
            $details
                ->setPost($post)
                ->setTemplate("Amasty_Blog::post/footer.phtml");;
            return $details->toHtml();
        }
        return false;
    }

    public function stripTags($data, $allowableTags = null, $allowHtmlEntities = false)
    {
        $result = strip_tags($data, $allowableTags);
        return $allowHtmlEntities ? $result : $this->escapeHtml($result, $allowableTags);
    }

    public function escapeHtml($data, $allowedTags = null)
    {
        if (is_array($data)) {
            $result = array();
            foreach ($data as $item) {
                $result[] = $this->escapeHtml($item);
            }
        } else {
            // process single item
            if (strlen($data)) {
                if (is_array($allowedTags) and !empty($allowedTags)) {
                    $allowed = implode('|', $allowedTags);
                    $result = preg_replace('/<([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)>/si', '##$1$2$3##', $data);
                    $result = htmlspecialchars($result, ENT_COMPAT, 'UTF-8', false);
                    $result = preg_replace('/##([\/\s\r\n]*)(' . $allowed . ')([\/\s\r\n]*)##/si', '<$1$2$3>', $result);
                } else {
                    $result = htmlspecialchars($data, ENT_COMPAT, 'UTF-8', false);
                }
            } else {
                $result = $data;
            }
        }
        return $result;
    }

    public function loadCommentorName()
    {
        return $this->session->getData(self::KEY_CUSTOMER_NAME);
    }

    public function loadCommentorEmail()
    {
        return $this->session->getData(self::KEY_CUSTOMER_EMAIL);
    }

    public function loadIsSubscribed()
    {
        return $this->session->getData(self::KEY_IS_SUBSCRIBED);
    }
}