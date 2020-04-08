<?php

namespace MageArray\News\Model;

use Magento\Framework\DataObject;
use Magento\Sitemap\Helper\Data;
use Magento\Sitemap\Model\Sitemap;

/**
 * Class SitemapPlugin
 * @package MageArray\News\Model
 */
class SitemapPlugin
{
    /**
     * @var
     */
    protected $_postFactory;

    /**
     * @var Data
     */
    protected $_sitemapData;

    /**
     * @var bool
     */
    protected $_sitemapItemsAdded = false;

    /**
     * SitemapPlugin constructor.
     * @param Data $sitemapData
     * @param NewspostFactory $newspostFactory
     */
    public function __construct(
        Data $sitemapData,
        NewspostFactory $newspostFactory
    ) {
        $this->_sitemapData = $sitemapData;
        $this->_newspostFactory = $newspostFactory;
    }

    /**
     * @param Sitemap $subject
     */
    public function beforeGetSitemapItems(Sitemap $subject)
    {
        if ($this->_sitemapItemsAdded) {
            return;
        }

        $this->_sitemapData;
        $subject->getStoreId();

        $sitemapItem = new DataObject(
            [
                'changefreq' => 'weekly',
                'priority' => '0.25',
                'collection' => $this->_newspostFactory
                    ->create()->getCollection()->setPostFilter(),
            ]
        );

        $subject->addSitemapItems($sitemapItem);

        $this->_sitemapItemsAdded = true;
    }
}
