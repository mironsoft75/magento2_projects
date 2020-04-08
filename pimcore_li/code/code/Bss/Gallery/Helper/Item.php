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

class Item extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @var \Bss\Gallery\Model\Item
     */
    protected $_item;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Bss\Gallery\Model\Item $item
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Bss\Gallery\Model\Item $item,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->_item = $item;
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Return a gallery item from given item id.
     *
     * @param Action $action
     * @param null $itemId
     * @return \Magento\Framework\View\Result\Page|bool
     */
    public function prepareResultItem(Action $action, $itemId = null)
    {
        if ($itemId !== null && $itemId !== $this->_item->getId()) {
            $delimiterPosition = strrpos($itemId, '|');
            if ($delimiterPosition) {
                $itemId = substr($itemId, 0, $delimiterPosition);
            }
            if (!$this->_item->load($itemId)) {
                return false;
            }
        }
        if (!$this->_item->getId()) {
            return false;
        }
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        // We can add our own custom page handles for layout easily.
        $resultPage->addHandle('gallery_item_view');
        // This will generate a layout handle like: gallery_item_view_id_1
        // giving us a unique handle to target specific gallery items if we wish to.
        $resultPage->addPageLayoutHandles(['id' => $this->_item->getId()]);
        // Magento is event driven after all, lets remember to dispatch our own, to help people
        // who might want to add additional functionality, or filter the posts somehow!
        $this->_eventManager->dispatch(
            'bss_gallery_item_render',
            ['item' => $this->_item, 'controller_action' => $action]
        );
        return $resultPage;
    }
}
