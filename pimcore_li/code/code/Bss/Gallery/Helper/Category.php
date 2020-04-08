<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Helper;

use Magento\Framework\App\Action\Action;
use Magento\Store\Model\ScopeInterface;

class Category extends \Magento\Framework\App\Helper\AbstractHelper
{

    const XML_PATH_ENABLED = 'bss_gallery/general/enable';
    const XML_PATH_IMAGE_PER_PAGE = 'bss_gallery/general/image_per_page';
    const XML_PATH_LAYOUT_TYPE = 'bss_gallery/general/layout_type';
    const XML_PATH_AUTO_LOAD = 'bss_gallery/general/autoload';
    const XML_PATH_PAGE_SPEED = 'bss_gallery/general/page_speed';
    const XML_PATH_TITLE_POSITION = 'bss_gallery/general/title_position';
    const XML_PATH_TRANSITION_EFFECT = 'bss_gallery/general/transition_effect';
    const XML_PATH_ITEM_LAYOUT_TYPE = 'bss_gallery/general/item_layout_type';


    protected $_category;

    protected $resultPageFactory;

    protected $_scopeConfig;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Bss\Gallery\Model\Category $category
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Bss\Gallery\Model\Category $category,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->_category = $category;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->_scopeConfig = $context->getScopeConfig();
    }

    public function isEnabledInFrontend($store = null)
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_ENABLED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getItemPerPage()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_IMAGE_PER_PAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getLayoutType()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_LAYOUT_TYPE,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function isAutoLoad()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_AUTO_LOAD,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getPageSpeed()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_PAGE_SPEED,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getTitlePosition()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_TITLE_POSITION,
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getTransitionEffect()
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_TRANSITION_EFFECT,
            ScopeInterface::SCOPE_STORE
        );
    }

    // public function getItemLayoutType()
    // {
    //    return $this->_scopeConfig->getValue(
    //       self::XML_PATH_ITEM_LAYOUT_TYPE,
    //       ScopeInterface::SCOPE_STORE
    //    );
    // }

    /**
     * Return a gallery category from given category id.
     *
     * @param Action $action
     * @param null $categoryId
     * @return \Magento\Framework\View\Result\Page|bool
     */
    public function prepareResultCategory(Action $action, $categoryId = null)
    {
        if ($categoryId !== null && $categoryId !== $this->_category->getId()) {
            $delimiterPosition = strrpos($categoryId, '|');
            if ($delimiterPosition) {
                $categoryId = substr($categoryId, 0, $delimiterPosition);
            }
            if (!$this->_category->load($categoryId)) {
                return false;
            }
        }
        if (!$this->_category->getId()) {
            return false;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        // We can add our own custom page handles for layout easily.
        $resultPage->addHandle('gallery_category_view');
        // This will generate a layout handle like: gallery_category_view_id_1
        // giving us a unique handle to target specific gallery categories if we wish to.
        $resultPage->addPageLayoutHandles(['id' => $this->_category->getId()]);
        // Magento is event driven after all, lets remember to dispatch our own, to help people
        // who might want to add additional functionality, or filter the posts somehow!
        $this->_eventManager->dispatch(
            'bss_gallery_category_render',
            ['category' => $this->_category, 'controller_action' => $action]
        );
        return $resultPage;
    }


}
