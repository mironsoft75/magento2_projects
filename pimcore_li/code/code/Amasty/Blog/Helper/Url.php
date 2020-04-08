<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Helper;

class Url extends \Magento\Framework\App\Helper\AbstractHelper
{

    const ROUTE_POST = 'post';
    const ROUTE_CATEGORY = 'category';
    const ROUTE_TAG = 'tag';
    const ROUTE_ARCHIVE = 'archive';
    const ROUTE_SEARCH = 'search';

    protected $_storeId;
    
    /**
     * @var Settings
     */
    private $settings;
    /**
     * @var \Magento\Framework\App\Helper\Context
     */
    private $context;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManagerInterface;
    /**
     * @var \Magento\Theme\Block\Html\Pager
     */
    private $pager;

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManagerInterface;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    private $messageManager;

    public function __construct(
        \Amasty\Blog\Helper\Settings $settings,
        \Magento\Framework\ObjectManagerInterface $objectManagerInterface,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \Magento\Theme\Block\Html\Pager $pager,
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\Message\ManagerInterface $messageManager
    ) {
        parent::__construct($context);
        $this->settings = $settings;
        $this->context = $context;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->pager = $pager;
        $this->messageManager = $messageManager;
        $this->objectManagerInterface = $objectManagerInterface;
    }

    /**
     * @param $url
     * @return bool is url valid
     */
    public function validate($url)
    {
        $isUrlValid = true;

        if (strpos($url, '/') !== false) {
            $isUrlValid = false;
            $this->messageManager->addErrorMessage(__('URL route and URL key are not allow /'));
        }

        return $isUrlValid;
    }

    public function prepare($url)
    {
        return str_replace('/', '', $url);
    }

    public function generate($title)
    {
        $title = preg_replace('/[«»""!?,.!@£$%^&*{};:()]+/', '', strtolower($title));
        $key=preg_replace('/[^A-Za-z0-9-]+/', '-', $title);
        return $key;
    }

    public function getPostId($identifier, $forceStore = false)
    {
        $clean = $this->_cleanUrl($identifier);
        
        $collection = $this->objectManagerInterface->create('Amasty\Blog\Model\ResourceModel\Posts\Collection');
        
        $collection
            ->addFieldToFilter('url_key', $clean)
            ->addFieldToFilter('status',
                [
                    'in' => [
                        \Amasty\Blog\Model\Posts::STATUS_ENABLED,
                        \Amasty\Blog\Model\Posts::STATUS_HIDDEN
                    ]
                ]
            )
            ->setUrlKeyIsNotNull()
        ;

        if (!$this->storeManagerInterface->isSingleStoreMode() && !$forceStore){
            $collection->addStoreFilter($this->storeManagerInterface->getStore()->getId());
        }

        foreach ($collection as $post){
            return $post->getId();
        }

        return false;
    }

    protected function _cleanUrl($identifier, $page = 1)
    {
        $clean = substr($identifier, strlen($this->getRoute()), strlen($identifier));
        $clean = trim($clean, "/");
        $clean = str_replace(array(
            $this->getUrlPostfix($page),
        ), "", $clean);
        return $clean;
    }

    public function getRoute()
    {
        return trim( $this->settings->getSeoRoute() );
    }

    public function getUrlPostfix($page = 1)
    {
        $postfix = $this->settings->getBlogPostfix();
        if ($page > 1){
            return "/{$page}{$postfix}";
        } else {
            return $postfix;
        }

    }

    public function getUrl($id = null, $route = self::ROUTE_POST, $page = 1)
    {
        $storeId = $this->getStoreId() ? $this->getStoreId() : $this->storeManagerInterface->getStore()->getId();
        $baseUrl =$this->storeManagerInterface->getStore($storeId)->getBaseUrl();
        $url = $baseUrl.$this->getRoute();

        if ($id){

            if ($route == self::ROUTE_POST){
                
                $post = $this->objectManagerInterface->create('Amasty\Blog\Model\Posts');
                $post->load($id);
                if ($post->getUrlKey()){
                    $url .= "/".$post->getUrlKey();
                }


            } elseif ($route == self::ROUTE_CATEGORY) {
                $category = $this->objectManagerInterface->create('Amasty\Blog\Model\Categories');
                $category->load($id);
                if ($category->getUrlKey()){
                    $url .= "/".self::ROUTE_CATEGORY."/".$category->getUrlKey();
                }

            } elseif ($route == self::ROUTE_TAG) {
                $tag = $this->objectManagerInterface->create('Amasty\Blog\Model\Tags');
                $tag->load($id);
                $url .= "/".self::ROUTE_TAG."/". urlencode($tag->getUrlKey());

            } elseif ($route == self::ROUTE_ARCHIVE) {

                $url .= "/".self::ROUTE_ARCHIVE."/". $id;

            }

        } else {

            if ($route == self::ROUTE_SEARCH){

                $url .= "/".self::ROUTE_SEARCH;
            }
        }

        $url .= $this->getUrlPostfix($page);

        return $url;
    }

    public function getCategoryId($identifier, $page = 1)
    {
        $clean = $this->_cleanUrl($identifier, $page);

        if (strpos($clean, "/") === false){
            return false;
        }

        $parts = explode("/", $clean);

        if ( (count($parts) != 2) || $parts[0] !== self::ROUTE_CATEGORY ){
            return false;
        }

        $categoryUrlKey = $parts[1];

        /** @var \Amasty\Blog\Model\ResourceModel\Categories\Collection $collection */

        $collection = $this->objectManagerInterface->create('Amasty\Blog\Model\ResourceModel\Categories\Collection');

        $collection
            ->addFieldToFilter('status', \Amasty\Blog\Model\Categories::STATUS_ENABLED)
            ->addFieldToFilter('url_key', $categoryUrlKey)
        ;

        if (!$this->storeManagerInterface->isSingleStoreMode()){
            $collection->addStoreFilter($this->storeManagerInterface->getStore()->getId());
        }

        foreach ($collection as $category){
            return $category->getId();
        }

        return false;
    }

    public function getTagId($identifier, $page = 1)
    {
        $clean = $this->_cleanUrl($identifier, $page);

        if (strpos($clean, "/") === false){
            return false;
        }

        $parts = explode("/", $clean);

        if ( (count($parts) != 2) || $parts[0] !== self::ROUTE_TAG ){
            return false;
        }

        $tagUrlKey = $parts[1];

        $tagUrlKey = urldecode($tagUrlKey);
        $tag = $this->objectManagerInterface->create('Amasty\Blog\Model\Tags');
        $tag->load($tagUrlKey, 'url_key');

        if ($tag->getId()){
            return $tag->getId();
        }

        return false;
    }

    public function isIndexRequest($identifier, $page = 1)
    {
        return str_replace(array(
            $this->getUrlPostfix($page),
            '/',
            '.html',
            '.htm',
        ), "", $identifier) == $this->getRoute();
    }

    public function getIsSearchRequest($identifier, $page = 1)
    {
        $clean = $this->_cleanUrl($identifier, $page);

        if (strpos($clean, "/") !== false){
            return false;
        }

        if ($clean === self::ROUTE_SEARCH){
            return true;
        }

        return false;
    }

    public function isRightSyntax($identifier, $postId = null, $route = self::ROUTE_POST, $page = 1)
    {
        if (!$this->settings->getRedirectToSeoFormattedUrl()){
            return true;
        }
        
        $stdPage = !!$this->_getRequest()->getParam($this->pager->getPageVarName());
        $required = str_replace($this->storeManagerInterface->getStore()->getBaseUrl(), "", $this->getUrl($postId, $route, $page));
        return (strtolower($identifier) == strtolower($required) && !$stdPage);
    }
    
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }

    public function getStoreId()
    {
        return $this->_storeId;
    }
    
    public function generateSlug($title)
    {
        $title = urldecode($title);
        $title = strtolower(preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), $title));
        return $title;
    }

}
