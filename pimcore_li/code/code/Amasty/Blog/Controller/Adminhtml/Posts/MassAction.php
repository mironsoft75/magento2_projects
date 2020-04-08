<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Posts;

class MassAction extends \Amasty\Blog\Controller\Adminhtml\Posts
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('posts');
        $action = $this->getRequest()->getParam('action');
        if ($ids && in_array($action, ['activate', 'inactivate', 'delete'])) {
            try {
                /**
                 * @var $collection \Amasty\Blog\Model\ResourceModel\Posts\Collection
                 */
                $collection = $this->_objectManager->create('Amasty\Blog\Model\ResourceModel\Posts\Collection');

                $collection->addFieldToFilter('post_id', array('in'=>$ids));
                $collection->walk($action);
                switch($action) {
                    case 'activate':
                        $messageSuccess = __('You activated the post(s).');
                        break;
                    case 'inactivate':
                        $messageSuccess = __('You inactivated the post(s).');
                        break;
                    default:
                        $messageSuccess = __('You deleted the post(s).');
                        break;
                }
                $this->messageManager->addSuccess($messageSuccess);
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete post(s) right now. Please review the log and try again.').$e->getMessage()
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_redirect('*/*/');
    }
}