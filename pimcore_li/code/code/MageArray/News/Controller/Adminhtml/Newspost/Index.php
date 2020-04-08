<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->setActiveMenu('MageArray_News::newspost');
        $resultPage->addBreadcrumb(
            __('CMS'),
            __('CMS')
        );
        $resultPage->addBreadcrumb(
            __('Manage  News Post'),
            __('Manage News Posts')
        );
        $resultPage->getConfig()
            ->getTitle()->prepend(__('Manage News Posts'));

        return $resultPage;
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MageArray_News::newscomment');
    }
}
