<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Plugins\XmlSitemap\Model;

use Amasty\XmlSitemap\Model\Sitemap as NativeSitemap;

class Sitemap
{
    /**
     * @var \Amasty\Blog\Model\SitemapFactory
     */
    private $sitemapFactory;

    public function __construct(
        \Amasty\Blog\Model\SitemapFactory $sitemapFactory
    ) {
        $this->sitemapFactory = $sitemapFactory;
    }

    /**
     * @param NativeSitemap $subgect
     * @param \Closure $proceed
     * @param $storeId
     * @return array
     */
    public function aroundGetBlogProLinks(NativeSitemap $subgect, \Closure $proceed, $storeId)
    {
        /** @var \Amasty\Blog\Model\Sitemap $blogSitemap */
        $blogSitemap = $this->sitemapFactory->create();
        $blogLinks = $blogSitemap->generateLinks($storeId);

        return $blogLinks;
    }
}
