<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Tags;

class Index extends \Amasty\Blog\Controller\Adminhtml\Categories
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Blog::tags');
        $resultPage->getConfig()->getTitle()->prepend(__('Tags'));
        $resultPage->addBreadcrumb(__('Tags'), __('Tags'));
        return $resultPage;
    }
}