<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block;

class Layout extends \Magento\Framework\View\Element\Template
{
    const CONFIG_XML_PATH = 'layout';
    const ROUTE_LIST = 'list';
    const ROUTE_POST = 'post';

    const CACHE_DATA_PREFIX = 'am_blog_';

    protected $_askedBlockIds = array();
    protected $_desktop = array();
    protected $_mobile = array();

    protected $_summary = array();

    protected $_messagesContent = null;
    /**
     * @var \Amasty\Blog\Helper\Data
     */
    private $dataHelper;

    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    private $settingsHelper;

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate("Amasty_Blog::layout.phtml");
    }

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Amasty\Blog\Helper\Data $dataHelper,
        \Amasty\Blog\Helper\Settings $settingsHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->dataHelper = $dataHelper;
        $this->settingsHelper = $settingsHelper;
    }

    protected function _getBlogRoute()
    {
        if ($this->getRequest()->getActionName() == 'post'){
            return self::ROUTE_POST;
        } else {
            return self::ROUTE_LIST;
        }
    }

    protected function _loadPerZoneLayoutConfig($zone, &$target)
    {
        $key = sprintf("%s_%s", $zone, $this->_getBlogRoute());

        $config = [];
        switch ($key) {
            case 'mobile_list':
                $config = $this->settingsHelper->getMobileList();
                break;
            case 'mobile_post':
                $config = $this->settingsHelper->getMobilePost();
                break;
            case 'desktop_list':
                $config = $this->settingsHelper->getDesktopList();
                break;
            case 'desktop_post':
                $config = $this->settingsHelper->getDesktopPost();
                break;
        }
        
        $target = \Zend_Json::decode($config);

        return $this;
    }

    protected function _prepareLayoutConfig()
    {
        $this
            ->_loadPerZoneLayoutConfig('mobile', $this->_mobile)
            ->_loadPerZoneLayoutConfig('desktop', $this->_desktop)
        ;
    }

    protected function _prepareLayout()
    {
        $this->_prepareLayoutConfig();
        parent::_prepareLayout();
        return $this;
    }

    protected function _addBefore(&$target, $where, $alias)
    {
        if (isset($target[$where]) && is_array($target[$where])){
            if (!in_array($alias, $target[$where])){
                array_unshift($target[$where], $alias);
            }
        }
        return $this;
    }

    protected function _addAfter(&$target, $where, $alias)
    {
        if (isset($target[$where]) && is_array($target[$where])){
            if (!in_array($alias, $target[$where])){
                $target[$where][] = $alias;
            }
        }
        return $this;
    }

    public function addBefore($where, $alias)
    {
        $this->_addBefore($this->_desktop, $where, $alias);
        $this->_addBefore($this->_mobile, $where, $alias);

        return $this;
    }

    public function addAfter($where, $alias)
    {
        $this->_addAfter($this->_desktop, $where, $alias);
        $this->_addAfter($this->_mobile, $where, $alias);

        return $this;
    }

    protected function _isBlockUsedIn(&$target, $alias)
    {
        $where = array(
            'left_side',
            'right_side',
            'content',
        );

        foreach ($where as $listKey){
            if (isset($target[$listKey]) && is_array($target[$listKey])){
                if (in_array($alias, $target[$listKey])){
                    return true;
                }
            }
        }

        return false;
    }

    public function isBlockUsed($alias)
    {
        return $this->_isBlockUsedIn($this->_mobile, $alias) || $this->_isBlockUsedIn($this->_desktop, $alias);
    }

    public function getContentBlockHtml($alias)
    {
        $content = $this->getChildBlock('layout_content');
        $id = 'amblog_content_'.str_replace("-", "_", $alias);
        if ($content && !$this->isAskedBefore($id)){

            $this->askBlock($id);
            return "<div id=\"{$id}\">".$content->getChildHtml($alias)."</div>";
        }
        return false;
    }

    public function getSidebarBlockHtml($alias)
    {
        $sidebar = $this->getChildBlock('layout_sidebar');
        $id = 'amblog_sidebar_'.str_replace("-", "_", $alias);
        if ($sidebar && !$this->isAskedBefore($id)){

            $this->askBlock($id);
            return "<div id=\"{$id}\">".$sidebar->getChildHtml($alias)."</div>";
        }
        return false;
    }

    public function getDesktopLayoutCode()
    {
        return isset($this->_desktop['layout']) ? $this->_desktop['layout'] : false;
    }

    public function getMobileLayoutCode()
    {
        return isset($this->_mobile['layout']) ? $this->_mobile['layout'] : false;
    }

    public function hasDesktopLeftColumn()
    {
        return in_array($this->getDesktopLayoutCode(), array('two-columns-left', 'three-columns'));
    }

    public function hasDesktopRightColumn()
    {
        return in_array($this->getDesktopLayoutCode(), array('two-columns-right', 'three-columns'));
    }

    public function hasMobileLeftColumn()
    {
        return in_array($this->getMobileLayoutCode(), array('two-columns-left', 'three-columns'));
    }

    public function hasMobileRightColumn()
    {
        return in_array($this->getMobileLayoutCode(), array('two-columns-right', 'three-columns'));
    }

    public function getDesktopBlocks($column)
    {
        if (isset($this->_desktop[$column]) && $this->_desktop[$column]){
            return $this->_desktop[$column];
        } else {
            return array();
        }
    }

    public function getMobileBlocks($column)
    {
        if (isset($this->_mobile[$column]) && $this->_mobile[$column]){
            return $this->_mobile[$column];
        } else {
            return array();
        }
    }

    public function askBlock($id)
    {
        if (!in_array($id, $this->_askedBlockIds)){
            $this->_askedBlockIds[] = $id;
        }
        return $this;
    }

    public function isAskedBefore($id)
    {
        return in_array($id, $this->_askedBlockIds);
    }

    public function getAskedBlockSelector()
    {
        $selectors = array();
        foreach ($this->_askedBlockIds as $id) {
            $selectors[] = "#".$id;
        }
        return implode(", ", $selectors);
    }
}