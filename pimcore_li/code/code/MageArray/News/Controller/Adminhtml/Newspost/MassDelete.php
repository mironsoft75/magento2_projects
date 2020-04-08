<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;

/**
 * Class MassDelete
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class MassDelete extends Newspost
{
    /**
     * @return $this
     */
    public function execute()
    {
        $newspostIds = $this->getRequest()->getParam('newspost');
        if (!is_array($newspostIds) || empty($newspostIds)) {
            $this->messageManager->addError(__('Please select news post(s).'));
        } else {
            try {
                foreach ($newspostIds as $postId) {
                    $post = $this->_newspostFactory->create()
                        ->load($postId);
                    $post->delete();
                }
                $this->messageManager->addSuccess(
                    __(
                        'A total of %1 record(s) have been deleted.',
                        count($newspostIds)
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
