<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Posts;

class Duplicate extends \Amasty\Blog\Controller\Adminhtml\Posts
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
        if (!$id) {
            $this->messageManager->addError(__('Please select a post to duplicate.'));
            return $this->_redirect('*/*');
        }
        try {
            $model = $this->_objectManager->create('Amasty\Blog\Model\Posts')->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('*/*');
                return false;
            }

            $post = clone $model;
            $post->setId(null);
            $post->setUrlKey($post->getUrlKey().'-duplicate');
            $post->save();

            $this->messageManager->addSuccess(__('The post has been duplicated.'));

            return $this->_redirect('*/*/edit', array('id' => $post->getId()));
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $this->messageManager->addError($e->getMessage());
            $this->_redirect('*/*');
            return false;
        } catch (\Exception $e) {
            $this->messageManager->addError(
                __('Something went wrong while saving the item data. Please review the error log.')
            );
            $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
            $this->_redirect('*/*');
            return false;
        }
    }
}