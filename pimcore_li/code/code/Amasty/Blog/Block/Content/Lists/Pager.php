<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Content\Lists;

class Pager extends \Magento\Theme\Block\Html\Pager
{

    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settings;

    protected $_object = null;

    protected $_urlPostfix = null;


    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Settings $settings,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->settings = $settings;
    }
    
    public function setPagerObject($object)
    {
        $this->_object = $object;
        return $this;
    }

    public function getPagerObject()
    {
        return $this->_object;
    }

    public function getPageUrl($page)
    {
        return $this->getPagerObject()->getUrl(null, $page).$this->getUrlPostfix();
    }

    public function isOldStyle()
    {
        return false;
    }

    public function getColorClass()
    {
        return $this->settings->getIconColorClass();
    }

    /**
     * Get Url Postfix
     *
     * @return null
     */
    public function getUrlPostfix()
    {
        return $this->_urlPostfix;
    }

    /**
     * Set URL postfix
     *
     * @param $urlPostfix
     * @return $this
     */
    public function setUrlPostfix($urlPostfix)
    {
        $this->_urlPostfix = $urlPostfix;
        return $this;
    }

    /**
     * Return current page
     *
     * @return int
     */
    public function getCurrentPage()
    {
        if (is_object($this->_collection)) {
            return $this->_collection->getCurPage();
        }

        $pageNum = (int) $this->getRequest()->getParam($this->getPageVarName());
        return $pageNum ? $pageNum : 1;
    }
}