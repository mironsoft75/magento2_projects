<?php

namespace MageArray\News\Block;

use MageArray\News\Helper\Data;
use MageArray\News\Model\NewscommentFactory;
use MageArray\News\Model\NewspostFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

/**
 * Class News
 * @package MageArray\News\Block
 */
class News extends Template
{
    /**
     * @var NewspostFactory
     */
    protected $_modelNewsFactory;
    /**
     * @var null
     */
    protected $_postCollection = null;
    /**
     * @var NewscommentFactory
     */
    protected $_newscommentFactory;
    /**
     * @var FilterProvider
     */
    protected $_filterProvider;
    /**
     * @var Registry
     */
    protected $_coreRegistry;

    /**
     * News constructor.
     * @param Context $context
     * @param NewspostFactory $modelNewsFactory
     * @param NewscommentFactory $newscommentFactory
     * @param Data $dataHelper
     * @param Registry $coreRegistry
     * @param FilterProvider $filterProvider
     */
    public function __construct(
        Context $context,
        NewspostFactory $modelNewsFactory,
        NewscommentFactory $newscommentFactory,
        Data $dataHelper,
        Registry $coreRegistry,
        FilterProvider $filterProvider
    ) {
        parent::__construct($context);
        $this->_modelNewsFactory = $modelNewsFactory;
        $this->_newscommentFactory = $newscommentFactory;
        $this->_dataHelper = $dataHelper;
        $this->_coreRegistry = $coreRegistry;
        $this->_filterProvider = $filterProvider;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        $now = date('Y-m-d');
        $newsModel = $this->_modelNewsFactory->create();
        $newsCollection = $newsModel->getCollection()
            ->setActiveFilter(true)
            ->setPostFilter()
            ->setStoreFilter($this->getStoreId())
            ->addFieldToFilter('publish_date', ['lteq' => $now]);
        $s = $this->getRequest()->getParam('s');
        if ($s) {
            $newsCollection->addFieldToFilter(
                ['title', 'short_content', 'content', 'tags'],
                [
                    ['like' => '%' . $s . '%'],
                    ['like' => '%' . $s . '%'],
                    ['like' => '%' . $s . '%'],
                    ['like' => '%' . $s . '%']
                ]
            );
        }
        return $newsCollection;
    }

    /**
     * @return mixed|null
     */
    public function getCollection()
    {
        if (empty($this->_postCollection)) {
            $this->_postCollection = $this->getPosts();
            $this->_postCollection
                ->setCurPage($this->getCurrentPage());
            $this->_postCollection
                ->setPageSize($this->_dataHelper->getPostPerPage());
            $this->_postCollection
                ->setOrder(
                    'publish_date',
                    $this->_dataHelper->getSortOrder()
                );
        }
        return $this->_postCollection;
    }

    /**
     * @param $id
     * @return string
     * @throws \Exception
     */
    public function getShortDescription($id)
    {
        $newsModel = $this->_modelNewsFactory->create();
        $shortContent = $newsModel->load($id)->getShortContent();
        return $this->_filterProvider->getBlockFilter()->filter($shortContent);
    }

    /**
     * @return mixed
     */
    public function getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * @return int
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getComments($id)
    {
        $newscommentModel = $this->_newscommentFactory->create();
        $newsCollection = $newscommentModel
            ->getCollection()
            ->addFieldToFilter('newspost_id', $id)
            ->addFieldToFilter('comment_status', 1);
        $counts = count($newsCollection);
        return $counts;
    }

    /**
     * @return int|mixed
     */
    public function getCurrentPage()
    {
        return $this->getData('current_page') ?
            $this->getData('current_page') : 1;
    }

    /**
     * @return null|string
     */
    public function getPager()
    {
        $pager = $this->getChildBlock('post_list_pager');
        if ($pager instanceof DataObject) {
            $postPerPage = $this->_dataHelper->getPostPerPage();
            $pager->setAvailableLimit([$postPerPage => $postPerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(true);
            return $pager->toHtml();
        }
        return null;
    }

    /**
     * @param $item
     * @param $width
     * @return bool|string
     */
    public function getImageUrl($item, $width)
    {
        return $this->_dataHelper->resize($item, $width);
    }

    /**
     * @param $item
     * @param $width
     * @return bool|string
     */
    public function getThumbImageUrl($item, $width)
    {
        return $this->_dataHelper->resizeThumb($item, $width);
    }
}
