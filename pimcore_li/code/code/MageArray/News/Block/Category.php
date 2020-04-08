<?php

namespace MageArray\News\Block;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Categories;
use MageArray\News\Model\NewscatFactory;
use MageArray\News\Model\NewscommentFactory;
use MageArray\News\Model\NewspostFactory;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Category
 * @package MageArray\News\Block
 */
class Category extends Template
{
    /**
     * @var null
     */
    protected $_postCollection = null;
    /**
     * @var NewspostFactory
     */
    protected $_modelNewsFactory;
    /**
     * @var NewscommentFactory
     */
    protected $_newscommentFactory;
    /**
     * @var NewscatFactory
     */
    protected $_newscatFactory;
    /**
     * @var Categories
     */
    protected $_categories;
    /**
     * @var FilterProvider
     */
    protected $_filterProvider;

    /**
     * @return $this
     */
    public function _prepareLayout()
    {
        $dataCat = $this->getCategory();
        if ($dataCat['cat_meta_title']) {
            $metaTitle = $dataCat['cat_meta_title'];
        } else {
            $metaTitle = $dataCat['cat_name'];
        }
        $this->pageConfig->getTitle()->set($metaTitle);
        $this->pageConfig->setKeywords($dataCat['cat_meta_keywords']);
        $this->pageConfig->setDescription($dataCat['cat_meta_description']);
        $pageMainTitle = $this->getLayout()->getBlock('page.main.title');
        if ($pageMainTitle) {
            $pageMainTitle->setPageTitle($dataCat['cat_name']);
        }
        return parent::_prepareLayout();
    }

    /**
     * Category constructor.
     * @param Context $context
     * @param NewspostFactory $modelNewsFactory
     * @param NewscommentFactory $newscommentFactory
     * @param NewscatFactory $newscatFactory
     * @param Data $dataHelper
     * @param Categories $categories
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Context $context,
        NewspostFactory $modelNewsFactory,
        NewscommentFactory $newscommentFactory,
        NewscatFactory $newscatFactory,
        Data $dataHelper,
        Categories $categories,
        FilterProvider $filterProvider,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_modelNewsFactory = $modelNewsFactory;
        $this->_newscommentFactory = $newscommentFactory;
        $this->_newscatFactory = $newscatFactory;
        $this->_dataHelper = $dataHelper;
        $this->_categories = $categories;
        $this->_filterProvider = $filterProvider;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPosts($id)
    {
        $now = date('Y-m-d');
        $newsModel = $this->_modelNewsFactory->create();
        $newsCollection = $newsModel->getCollection()
            ->addFieldToFilter('category', ['finset' => $id])
            ->setActiveFilter(true)
            ->setPostFilter()
            ->setStoreFilter($this->getStoreId())
            ->addFieldToFilter('publish_date', ['lteq' => $now]);

        return $newsCollection;
    }

    /**
     * @return mixed|null
     */
    public function getCollection()
    {
        $id = $this->getRequest()->getParam('cat');
        if (empty($this->_postCollection)) {
            $this->_postCollection = $this->getPosts($id);
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
    public function getCatName()
    {
        $id = $this->getRequest()->getParam('cat');
        $newscatModel = $this->_newscatFactory->create();
        $newscatCollection = $newscatModel->load($id);
        $catName = $newscatCollection->getCatName();
        return $catName;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        if (!$this->hasData('category')) {
            $id = $this->getRequest()->getParam('cat');
            $newsCatModel = $this->_newscatFactory->create();
            $newsCat = $newsCatModel->load($id);
            $this->setData('category', $newsCat);
        }
        return $this->getData('category');
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
        $newsCollection = $newscommentModel->getCollection()
            ->addFieldToFilter('newspost_id', $id)
            ->addFieldToFilter('comment_status', 1);
        $counts = count($newsCollection);
        return $counts;
    }

    /**
     * @return mixed
     */
    public function getCategoryTree()
    {
        return $this->_categories->getfrontOptionArray();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTotalPosts($id)
    {
        $now = date('Y-m-d');
        $post = $this->_modelNewsFactory->create();
        $postCollection = $post->getCollection()
            ->addFieldToFilter('category', ['finset' => $id])
            ->setActiveFilter(true)
            ->setPostFilter()
            ->setStoreFilter($this->getStoreId())
            ->addFieldToFilter('publish_date', ['lteq' => $now]);
        $postCount = count($postCollection);
        return $postCount;
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
        $pager = $this->getChildBlock('cat_list_pager');
        if ($pager instanceof DataObject) {
            $postPerPage = $this->_dataHelper->getPostPerPage();
            $pager->setAvailableLimit([$postPerPage => $postPerPage]);
            $pager->setTotalNum($this->getCollection()->getSize());
            $pager->setCollection($this->getCollection());
            $pager->setShowPerPage(true);
            $pager->setFrameLength(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame',
                    ScopeInterface::SCOPE_STORE
                )
            )->setJump(
                $this->_scopeConfig->getValue(
                    'design/pagination/pagination_frame_skip',
                    ScopeInterface::SCOPE_STORE
                )
            );
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
