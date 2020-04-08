<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Comments;

class Index extends \Amasty\Blog\Controller\Adminhtml\Comments
{

    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Amasty_Blog::comments');
        $resultPage->getConfig()->getTitle()->prepend(__('Comments'));
        $resultPage->addBreadcrumb(__('Comments'), __('Comments'));
        return $resultPage;
    }
}