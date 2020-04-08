<?php
namespace Softprodigy\Bluedart\Controller\Adminhtml\Manifest;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
/**
 * Description of Generate
 *
 * @author mannu
 */
class History extends \Magento\Backend\App\Action {
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    /**
     * Check the permission to run it
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Softprodigy_Bluedart::Bluedart');
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
        $resultPage->setActiveMenu('Softprodigy_Bluedart::Bluedart');
        $resultPage->getConfig()->getTitle()->prepend(__('Manifest History'));
        return $resultPage;
    }

}
