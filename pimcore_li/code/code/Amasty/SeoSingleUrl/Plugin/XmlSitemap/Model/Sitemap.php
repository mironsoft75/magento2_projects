<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_SeoSingleUrl
 */


namespace Amasty\SeoSingleUrl\Plugin\XmlSitemap\Model;

use Amasty\SeoSingleUrl\Model\Source\Type;
use Amasty\XmlSitemap\Model\Sitemap as AmastySiteMap;

class Sitemap
{
    /**
     * @var \Amasty\SeoSingleUrl\Helper\Data
     */
    private $helper;

    public function __construct(
        \Amasty\SeoSingleUrl\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    public function aroundGetProductUrl(
        AmastySiteMap $subject,
        \Closure $proceed,
        $product
    ) {
        $type = $this->helper->getModuleConfig('general/product_url_type');

        if ($type !== Type::DEFAULT_RULES) {
            $url = $this->helper->generateSeoUrl($product->getId(), $product->getStoreId());
        } else {
            $url = $proceed($product);
        }

        return  $url;
    }
}
