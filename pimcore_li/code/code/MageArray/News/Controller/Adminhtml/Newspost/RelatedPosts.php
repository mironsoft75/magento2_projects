<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;

/**
 * Class RelatedPosts
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class RelatedPosts extends Newspost
{
    /**
     *
     */
    public function execute()
    {
        if (empty($this->_model)) {
            $this->_model = $this->_newspostFactory->create();

            $id = (int)$this->getRequest()->getParam('newspost_id');
            if ($id) {
                $this->_model->load($id);
            }
        }
        $this->_coreRegistry->register('related_post_model', $this->_model);
        $this->_view->loadLayout()
            ->getLayout()
            ->getBlock('magearray_news_relatedposts')
            ->setPostsRelated(
                $this->getRequest()->getPost('posts_related', null)
            );
        $this->_view->renderLayout();
    }
}
