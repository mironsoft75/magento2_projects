<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Adminhtml\Posts;

class Delete extends \Amasty\Blog\Controller\Adminhtml\Comments
{
    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = (int)$this->getRequest()->getParam('id');
        if ($id) {
            try {
                $model = $this->_objectManager->create('Amasty\Blog\Model\Posts');
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('You deleted post.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());

                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('We can\'t find a post to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
