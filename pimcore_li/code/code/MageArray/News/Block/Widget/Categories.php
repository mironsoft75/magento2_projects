<?php

namespace MageArray\News\Block\Widget;

use MageArray\News\Helper\Data;
use MageArray\News\Model\Categories as CategoriesData;
use MageArray\News\Model\Newscat;
use MageArray\News\Model\NewscatFactory;
use MageArray\News\Model\Newspost;
use MageArray\News\Model\NewspostFactory;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Widget\Block\BlockInterface;

/**
 * Class Categories
 * @package MageArray\News\Block\Widget
 */
class Categories extends Template implements BlockInterface
{
    /**
     * @var string
     */
    protected $_template = 'widget/categories.phtml';
    /**
     * @var Data
     */
    protected $_dataHelper;
    /**
     * @var Newspost
     */
    protected $_post;
    /**
     * @var Newscat
     */
    protected $_cat;
    /**
     * @var NewspostFactory
     */
    protected $_newspostFactory;
    /**
     * @var NewscatFactory
     */
    protected $_newscatFactory;
    /**
     * @var CategoriesData
     */
    protected $_categories;

    /**
     * Categories constructor.
     * @param Context $context
     * @param Newscat $cat
     * @param Newspost $post
     * @param NewspostFactory $postFactory
     * @param NewscatFactory $catFactory
     * @param CategoriesData $categories
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Newscat $cat,
        Newspost $post,
        NewspostFactory $postFactory,
        NewscatFactory $catFactory,
        CategoriesData $categories,
        Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_post = $post;
        $this->_cat = $cat;
        $this->_newspostFactory = $postFactory;
        $this->_newscatFactory = $catFactory;
        $this->_dataHelper = $dataHelper;
        $this->_categories = $categories;
    }

    /**
     * @return $this
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        return $this;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getTotalPosts($id)
    {
        $now = date('Y-m-d');
        $post = $this->_newspostFactory->create();
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
     * @return mixed
     */
    public function getCategoryTree()
    {
        return $this->_categories->getfrontOptionArray();
    }

    /**
     * @param $catUrl
     * @return string
     */
    public function getCatUrl($catUrl)
    {
        $catPrefix = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/cat_prefix');
        $urlSuffixConfig = $this->_dataHelper
            ->getStoreConfig('magearray_news/general/url_suffix');
        $urlSuffix = "";
        if (!empty($urlSuffixConfig) && $urlSuffixConfig != "") {
            $urlSuffix = '.' . $urlSuffixConfig;
        }
        return $this->getUrl() . $catPrefix . '/' . $catUrl . $urlSuffix;
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
}
