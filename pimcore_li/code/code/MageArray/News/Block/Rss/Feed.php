<?php

namespace MageArray\News\Block\Rss;

use MageArray\News\Model\NewspostFactory;
use Magento\Cms\Model\Page;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Feed
 * @package MageArray\News\Block\Rss
 */
class Feed extends Template
{
    /**
     * @var
     */
    protected $_postCollection;
    /**
     * @var NewspostFactory
     */
    protected $_modelNewsFactory;

    /**
     * Feed constructor.
     * @param Context $context
     * @param NewspostFactory $modelNewsFactory
     */
    public function __construct(
        Context $context,
        NewspostFactory $modelNewsFactory
    ) {
        parent::__construct($context);
        $this->_modelNewsFactory = $modelNewsFactory;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->getUrl('news/rss/feed');
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->_scopeConfig
            ->getValue(
                'magearray_news/rss_feed/title',
                ScopeInterface::SCOPE_STORE
            );
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->_scopeConfig
            ->getValue(
                'magearray_news/rss_feed/description',
                ScopeInterface::SCOPE_STORE
            );
    }

    /**
     * @return array
     */
    public function getIdentities()
    {
        return [Page::CACHE_TAG . '_news_rss_feed'];
    }

    /**
     * @return mixed
     */
    public function getPostCollection()
    {
        $now = date('Y-m-d');
        $newsModel = $this->_modelNewsFactory->create()->load(2);
        $newsCollection = $newsModel->getCollection()
            ->setActiveFilter(true)
            ->setPostFilter()
            ->addFieldToFilter('publish_date', ['lteq' => $now]);

        return $newsCollection;
    }
}
