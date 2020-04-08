<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Tags;

class MassAction extends \Amasty\Blog\Controller\Adminhtml\Posts
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('tag');
        $action = $this->getRequest()->getParam('action');
        if ($ids && in_array($action, ['activate', 'inactivate', 'delete'])) {
            try {
                /**
                 * @var $collection \Amasty\Blog\Model\ResourceModel\Posts\Collection
                 */
                $collection = $this->_objectManager->create('Amasty\Blog\Model\ResourceModel\Tags\Collection');

                $collection->addFieldToFilter('tag_id', array('in'=>$ids));
                $collection->walk($action);
                $messageSuccess = __('You deleted the post(s).');
                $this->messageManager->addSuccess($messageSuccess);
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete tag(s) right now. Please review the log and try again.').$e->getMessage()
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_redirect('*/*/');
    }
}