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
namespace Bss\Gallery\Controller\CateView;

use \Magento\Framework\App\Action\Action;
use Magento\Framework\Registry;

class Index extends Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;

    protected $registry;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(\Magento\Framework\App\Action\Context $context, Registry $registry, \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory)
    {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->registry = $registry;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $category_id = $this->getRequest()->getParam('category_id', $this->getRequest()->getParam('id', false));
        /** @var \Bss\Gallery\Helper\Category $category_helper */
        $category_helper = $this->_objectManager->get('Bss\Gallery\Helper\Category');
        $result_page = $category_helper->prepareResultCategory($this, $category_id);
        if (!$result_page) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        $category = $this->_objectManager->create('Bss\Gallery\Model\Category')->load($category_id);
        $this->registry->register('category', $category);
        $result_page->getConfig()->getTitle()->set(__('Gallery Album View'));
        $result_page->getConfig()->setKeyWords($category->getCategoryMetaKeywords());
        $result_page->getConfig()->setDescription($category->getCategoryMetaDescription());
        // Add breadcrumb
        /** @var \Magento\Theme\Block\Html\Breadcrumbs */
        $breadcrumbs = $result_page->getLayout()->getBlock('breadcrumbs');
        if($breadcrumbs) {
            $breadcrumbs->addCrumb('home', ['label' => __('Home'), 'title' => __('Home'), 'link' => $this->_url->getUrl('')]);
            $breadcrumbs->addCrumb('gallery_category', ['label' => __('Gallery'), 'title' => __('Gallery'), 'link' => $this->_url->getUrl('gallery')]);
            $breadcrumbs->addCrumb('gallery_item', ['label' => __('Image'), 'title' => __('Image')]);
            return $result_page;
        }
        return $result_page;
    }
}
