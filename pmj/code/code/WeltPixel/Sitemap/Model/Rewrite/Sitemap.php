<?php
namespace WeltPixel\Sitemap\Model\Rewrite;

/**
 * Class Sitemap
 * @package WeltPixel\Sitemap\Model\Rewrite
 */
class Sitemap extends \Magento\Sitemap\Model\Sitemap
{
    /**
     * @return array
     */
    public function getSitemapItems() {
        return $this->_sitemapItems;
    }

    /**
     * Add a sitemap item to the array of sitemap items
     *
     * Not existed in core class prior 2.2 version
     *
     * @param \Magento\Framework\DataObject $sitemapItem
     * @return $this
     */
    public function addSitemapItem(\Magento\Framework\DataObject $sitemapItem)
    {
        $this->_sitemapItems[] = $sitemapItem;

        return $this;
    }

}
