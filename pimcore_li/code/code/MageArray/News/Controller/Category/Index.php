<?php

namespace MageArray\News\Controller\Category;

use MageArray\News\Helper\Index\View;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package MageArray\News\Controller\Category
 */
class Index extends Action
{
    /**
     * @var View
     */
    protected $_viewHelper;
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Index constructor.
     * @param Context $context
     * @param View $viewHelper
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        View $viewHelper,
        PageFactory $resultPageFactory
    ) {
        $this->_viewHelper = $viewHelper;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->_resultPageFactory->create(false, ['isIsolated' => true]);
        $pageNo = $this->getRequest()->getParam('p');
        $this->_viewHelper->prepareAndRenderCat($page, $pageNo);
        return $page;
    }
}
