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
namespace Bss\Gallery\Controller\Index;

use Magento\Framework\App\Action\Action;

class Index extends Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;
    protected $context;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->context = $context;
        parent::__construct($context);
    }

    /**
     * Gallery Index, shows a list of categories.
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('List Gallery Album'));
        $category_helper = $this->_objectManager->get('Bss\Gallery\Helper\Category');
        if (!$category_helper->isEnabledInFrontend()) {
            $this->_forward('defaultNoRoute');
        }
        // Add breadcrumb
        /** @var \Magento\Theme\Block\Html\Breadcrumbs */
        $breadcrumbs = $resultPage->getLayout()->getBlock('breadcrumbs');
        if(!$breadcrumbs)
            return $resultPage;
        $breadcrumbs->addCrumb('home',
            [
                'label' => __('Home'),
                'title' => __('Home'),
                'link' => $this->_url->getUrl('')
            ]
        );
        $breadcrumbs->addCrumb('gallery_category',
            [
                'label' => __('Gallery'),
                'title' => __('Gallery')
            ]
        );
        return $resultPage;
    }
}
