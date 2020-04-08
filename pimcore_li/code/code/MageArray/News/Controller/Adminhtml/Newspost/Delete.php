<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;

/**
 * Class Delete
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class Delete extends Newspost
{
    /**
     * @return $this
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('newspost_id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $model = $this->_newspostFactory->create()->load($id);
        if ($id && $model->getNewspostId()) {
            try {
                $model->delete();
                $this->messageManager
                    ->addSuccess(
                        __('The news post has been deleted.')
                    );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->messageManager
                    ->addError($e->getMessage());
                return $resultRedirect
                    ->setPath(
                        '*/*/edit',
                        ['newspost_id' => $id]
                    );
            }
        }
        $this->messageManager->addError(
            __('We can\'t find a news post to delete.')
        );
        return $resultRedirect->setPath('*/*/');
    }
}
