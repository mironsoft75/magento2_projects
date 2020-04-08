<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Adminhtml\Comments;

class MassAction extends \Amasty\Blog\Controller\Adminhtml\Comments
{
    public function execute()
    {
        $ids = $this->getRequest()->getParam('comments');
        $action = $this->getRequest()->getParam('action');
        if ($ids && in_array($action, ['activate', 'inactivate', 'delete'])) {
            try {
                /**
                 * @var $collection \Amasty\Blog\Model\ResourceModel\Comments\Collection
                 */
                $collection = $this->_objectManager->create('Amasty\Blog\Model\ResourceModel\Comments\Collection');

                $collection->addFieldToFilter('comment_id', array('in'=>$ids));
                $collection->walk($action);
                switch($action) {
                    case 'activate':
                        $messageSuccess = __('You activated the comment(s).');
                        break;
                    case 'inactivate':
                        $messageSuccess = __('You inactivated the comment(s).');
                        break;
                    default:
                        $messageSuccess = __('You deleted the comment(s).');
                        break;
                }
                $this->messageManager->addSuccess($messageSuccess);
                $this->_redirect('*/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete comment(s) right now. Please review the log and try again.').$e->getMessage()
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('*/*/');
                return;
            }
        }
        $this->_redirect('*/*/');
    }
}