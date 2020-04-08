<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Comments;

class Edit extends \Amasty\Blog\Controller\Adminhtml\Comments
{
    /**
     * Dispatch request
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');

        $replyTo = $this->getRequest()->getParam('reply_to_id');

        $model = $this->_objectManager->create('Amasty\Blog\Model\Comments');

        if ($replyTo) {
            $model->addData(['reply_to' => $replyTo]);
        }

        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('*/*');
                return;
            }
        }

        // set entered data if was error when we do save
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        } else {
            //$this->_prepareForEdit($model);
        }

        $this->_coreRegistry->register('current_amasty_blog_comment', $model);
        $this->_initAction();
        if($model->getCommentId()) {
            $title = __('Edit Comment `%1`', $model->getName());
        } elseif($replyTo) {
            $title = __("Reply to Comment `%1`", $replyTo);
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }

}