<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 10/11/18
 * Time: 7:34 PM
 */
namespace Codilar\StoneAndMetalRates\Controller\Adminhtml\Metal;


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
        $resultPage->addBreadcrumb(__('StoneAndMetalRates Metal'), __('StoneAndMetalRates Metal'));
        $resultPage->getConfig()->getTitle()->prepend(__('Metal'));;

        return $resultPage;
    }
}

