<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Comments;

class Reject extends \Amasty\Blog\Controller\Adminhtml\Comments
{
    public function execute()
    {
        $id = $this->getRequest()->getParam('comment_id');

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        /** @var \Amasty\Blog\Model\Comments $model */
        $model = $this->_objectManager->create('Amasty\Blog\Model\Comments')->load($id);
        if (!$model->getId()) {
            $this->messageManager->addError(__('This request no longer exists.'));
            return $resultRedirect->setPath('*/*/');
        }

        $model->setData(
            'status', \Amasty\Blog\Model\Comments::STATUS_REJECTED
        );

        try {
            $model->save();
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }

        return $resultRedirect->setPath('*/*/');
    }
}
