<?php

namespace Codilar\Image360\Controller\Adminhtml\Import;

use Codilar\Image360\Controller\Adminhtml\Import;

class Edit extends \Codilar\Image360\Controller\Adminhtml\Import
{
    /**
     * Edit action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Codilar_Codilar::codilar');
        $title = $resultPage->getConfig()->getTitle();
        $title->prepend('Codilar Toolbox');
        $title->prepend('Import images to Magic 360');
        return $resultPage;
    }
}
