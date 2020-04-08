<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Posts;

class Edit extends \Amasty\Blog\Controller\Adminhtml\Posts
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
        $model = $this->_objectManager->create('Amasty\Blog\Model\Posts');

        if ($id) {
            //$model->getTaggedPosts();
            $model->load($id);
            //$model->getTaggedPosts();
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

        $this->_coreRegistry->register('current_amasty_blog_post', $model);
        $this->_initAction();
        if($model->getId()) {
            $title = __('Edit Post `%1`', $model->getTitle());
        } else {
            $title = __("Add New Post");
        }
        $this->_view->getPage()->getConfig()->getTitle()->prepend($title);
        $this->_view->renderLayout();
    }

}