<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 9/11/18
 * Time: 1:45 PM
 */
namespace Codilar\StoneAndMetalRates\Controller\Adminhtml\Stone;


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
        $resultPage->addBreadcrumb(__('StoneAndMetalRates'), __('StoneAndMetalRates'));
        $resultPage->addBreadcrumb(__('StoneAndMetalRates Stone'), __('StoneAndMetalRates Stone'));
        $resultPage->getConfig()->getTitle()->prepend(__('Stone'));;

        return $resultPage;
    }
}

