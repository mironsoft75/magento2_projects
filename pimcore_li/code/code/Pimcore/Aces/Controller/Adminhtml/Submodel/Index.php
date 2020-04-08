<?php
/**
 * Created by pimcore.
 * Date: 15/9/18
 * Time: 4:44 PM
 */

namespace Pimcore\Aces\Controller\Adminhtml\Submodel;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->addBreadcrumb(__('Aces'), __('Aces'));
        $resultPage->addBreadcrumb(__('Aces Submodel'), __('Aces Submodel'));
        $resultPage->getConfig()->getTitle()->prepend(__('Aces Submodel'));;

        return $resultPage;
    }
}