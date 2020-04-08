<?php

namespace MageArray\News\Helper\Index;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Newspost;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\View\Result\Page as ResultPage;

/**
 * Class View
 * @package MageArray\News\Helper\Index
 */
class View extends AbstractHelper
{
    /**
     * View constructor.
     * @param Context $context
     * @param Data $dataHelper
     * @param Newspost $post
     */
    public function __construct(
        Context $context,
        Data $dataHelper,
        Newspost $post
    ) {
        parent::__construct($context);
        $this->_scopeConfig = $this->scopeConfig;
        $this->_post = $post;
        $this->_dataHelper = $dataHelper;
    }

    /**
     * @param ResultPage $resultPage
     * @param $layoutId
     * @return $this
     */
    public function initProductLayout(ResultPage $resultPage, $layoutId)
    {
        $postListLayout = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/' . $layoutId);
        $pageConfig = $resultPage->getConfig();
        $pageConfig->setPageLayout($postListLayout);
        $resultPage->getLayout()->getUpdate();
        $this->_request->getFullActionName();
        return $this;
    }

    /**
     * @param ResultPage $resultPage
     * @param $pageNo
     * @return $this
     */
    public function prepareAndRender(
        ResultPage $resultPage,
        $pageNo
    ) {
        $this->initProductLayout($resultPage, 'post_list_layout');
        $currentPage = (int)$pageNo;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $resultPage->getLayout();
        $listBlock = $resultPage->getLayout()->getBlock('newspost');
        $listBlock->setCurrentPage($currentPage);
        return $this;
    }

    /**
     * @param ResultPage $resultPage
     * @param $pageNo
     * @return $this
     */
    public function prepareAndRenderCat(ResultPage $resultPage, $pageNo)
    {
        $this->initProductLayout($resultPage, 'cat_layout');
        $currentPage = (int)$pageNo;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $resultPage->getLayout();
        $listBlock = $resultPage->getLayout()->getBlock('news.category');
        $listBlock->setCurrentPage($currentPage);
        return $this;
    }

    /**
     * @param ResultPage $resultPage
     * @param $postId
     * @param $pageNo
     * @return $this|bool
     */
    public function prepareAndRenderPost(ResultPage $resultPage, $postId, $pageNo)
    {
        if ($postId !== null && $postId !== $this->_post->getId()) {
            $delimiterPosition = strrpos($postId, '|');
            if ($delimiterPosition) {
                $postId = substr($postId, 0, $delimiterPosition);
            }

            if (!$this->_post->load($postId)) {
                return false;
            }
        }

        if (!$this->_post->getId()) {
            return false;
        }

        $resultPage->addHandle('news_post_view');
        $this->initProductLayout($resultPage, 'post_layout');
        $currentPage = (int)$pageNo;
        if ($currentPage < 1) {
            $currentPage = 1;
        }
        $resultPage->getLayout();
        $typeOfComment = $this->_dataHelper
            ->getStoreConfig('magearray_news/comments/type_of_comment');
        if ($typeOfComment) {
            $listBlock = $resultPage->getLayout()->getBlock('news.comment');
            $listBlock->setCurrentPage($currentPage);
        }
        return $this;
    }
}
