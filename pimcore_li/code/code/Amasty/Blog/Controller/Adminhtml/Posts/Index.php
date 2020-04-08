<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Posts;

class Index extends \Amasty\Blog\Controller\Adminhtml\Posts
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Blog::posts');
        $resultPage->getConfig()->getTitle()->prepend(__('Posts'));
        $resultPage->addBreadcrumb(__('Posts'), __('Posts'));
        return $resultPage;
    }
}