<?php

namespace Codilar\Rapnet\Controller\Adminhtml\RapnetProducts;

use Codilar\Rapnet\Helper\Data;
use Magento\Backend\App\Action\Context;

/**
 * Class UpdatePrice
 * @package Codilar\Rapnet\Controller\Adminhtml\RapnetProducts
 */
class UpdatePrice extends \Magento\Backend\App\Action
{

    /**
     * @var Data
     */
    protected $_rapnetHelper;

    /**
     * UpdatePrice constructor.
     * @param Context $context
     * @param Data $data
     */
    public function __construct(
        Context $context,
        Data $data
    )
    {
        $this->_rapnetHelper = $data;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $this->_rapnetHelper->updateRapnetProductPrice();
        $this->messageManager->addSuccessMessage(__('Rapnet Product Price updated Successfully.'));
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');
    }
}
