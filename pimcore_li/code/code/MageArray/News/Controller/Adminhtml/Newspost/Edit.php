<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;

/**
 * Class Edit
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class Edit extends Newspost
{

    /**
     *
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('newspost_id');
        $model = $this->_newspostFactory->create();

        if ($id) {
            $model->load($id);
            if (!$model->getNewspostId()) {
                $this->messageManager
                    ->addError(__('This post no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        $data = $this->_getSession()->getFormData(true);

        if (!empty($data)) {
            $model->setData($data);
        }
        $this->_coreRegistry->register('newspost_post', $model);
        $this->_view->loadLayout();
        $this->_view->getLayout()->initMessages();
        $this->_view->renderLayout();
    }
}
