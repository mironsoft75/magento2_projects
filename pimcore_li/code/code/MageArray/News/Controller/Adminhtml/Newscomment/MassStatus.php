<?php

namespace MageArray\News\Controller\Adminhtml\Newscomment;

use Magento\Backend\App\Action\Context;

/**
 * Class MassStatus
 * @package MageArray\News\Controller\Adminhtml\Newscomment
 */
class MassStatus extends \Magento\Backend\App\Action
{
    public function __construct(
        Context $context,
        \MageArray\News\Model\NewscommentFactory $newscommentFactory
    ) {
        parent::__construct($context);
        $this->_newscommentFactory = $newscommentFactory;
    }

    public function execute()
    {
        $newscommentIds = $this->getRequest()->getParam('comment_id');
        if (!is_array($newscommentIds) || empty($newscommentIds)) {
            $this->messageManager->addError(__('Please select Comment(s).'));
        } else {
            try {
                $status = (int)$this->getRequest()->getParam('status');
                foreach ($newscommentIds as $postId) {
                    $post = $this->_newscommentFactory->create()
                        ->load($postId);
                    $post->setCommentStatus($status)->save();
                }
                $this->messageManager->addSuccess(
                    __(
                        'A total of %1 record(s) have been updated.',
                        count($newscommentIds)
                    )
                );
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath('news/*/index');
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('MageArray_News::newscomment');
    }
}
