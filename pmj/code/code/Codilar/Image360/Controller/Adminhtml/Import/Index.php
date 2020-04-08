<?php

namespace Codilar\Image360\Controller\Adminhtml\Import;

use Codilar\Image360\Controller\Adminhtml\Import;

class Index extends \Codilar\Image360\Controller\Adminhtml\Import
{
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('image360/*/edit');
        return $resultRedirect;
    }
}
