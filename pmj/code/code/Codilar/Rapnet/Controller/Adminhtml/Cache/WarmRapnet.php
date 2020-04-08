<?php

namespace Codilar\Rapnet\Controller\Adminhtml\Cache;

use Magento\Backend\App\Action;
use Codilar\Rapnet\Model\WarmRapnetFilters;

/**
 * Class WarmRapnet
 * @package Codilar\Rapnet\Controller\Adminhtml\UrlRewrite
 */
class WarmRapnet extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var WarmRapnetFilters
     */
    private $warmRapnetFilters;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param WarmRapnetFilters $warmRapnetFilters
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        WarmRapnetFilters $warmRapnetFilters
    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->warmRapnetFilters = $warmRapnetFilters;
    }

    /**
     * Grid List page.
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        try {
            $this->warmRapnetFilters->warmRapnetFilters();
            $this->messageManager->addSuccessMessage(__('Filters warming in background.'));
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            return $resultRedirect->setPath('adminhtml/*');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        }
    }
    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed("Magento_Backend::warm_rapnet_cache");
    }
}
