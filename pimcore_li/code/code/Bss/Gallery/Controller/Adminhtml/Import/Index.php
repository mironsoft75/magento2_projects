<?php
namespace Bss\Gallery\Controller\Adminhtml\Import;

class Index extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory;

    /**
     * Index constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Import Gallery and Item'));
        return $resultPage;
    }

    /**
     * @return bool
     */
//    protected function _isAllowed()
//    {
//        switch ($this->getRequest()->getActionName()) {
//            case 'import':
//                return $this->_authorization->isAllowed('Bss_ReviewsImport::import_product_review');
//                break;
//            case 'export':
//                return $this->_authorization->isAllowed('Bss_ReviewsImport::export_product_review');
//                break;
//            default:
//                return $this->_authorization->isAllowed('Bss_ReviewsImport::importexport_product_review');
//                break;
//        }
//    }
}
