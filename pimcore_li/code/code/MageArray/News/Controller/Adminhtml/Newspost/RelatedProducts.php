<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;

/**
 * Class RelatedProducts
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class RelatedProducts extends Newspost
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
        $this->_coreRegistry->register('related_pro_model', $this->_model);
        $this->_view->loadLayout()
            ->getLayout()
            ->getBlock('magearray_news_relatedproducts')
            ->setProductsRelated(
                $this->getRequest()->getPost('products_related', null)
            );

        $this->_view->renderLayout();
    }
}
