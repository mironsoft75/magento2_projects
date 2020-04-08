<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_SeoToolKit
 */


namespace Amasty\SeoToolKit\Plugin;

use \Magento\Theme\Block\Html\Pager as NativePager;
use Magento\Framework\Url\Helper\Data as UrlHelper;

class Pager
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlInterface;

    /**
     * @var \Amasty\SeoToolKit\Helper\Config
     */
    private $config;

    /**
     * @var UrlHelper
     */
    private $urlHelper;

    public function __construct(
        \Magento\Framework\UrlInterface $urlInterface,
        UrlHelper $urlHelper,
        \Amasty\SeoToolKit\Helper\Config $config
    ) {
        $this->urlInterface = $urlInterface;
        $this->config = $config;
        $this->urlHelper = $urlHelper;
    }

    public function afterToHtml(
        NativePager $subject,
        $result
    ) {
        if (!$this->config->isPrevNextLinkEnabled()) {
            return $result;
        }

        $last = $subject->getLastPageNum();
        $current = $subject->getCurrentPage();

        $html = '';
        if ($current >= 2) {
            $prev = $current - 1;
            $url = $this->generateUrl($prev);
            $html .= '<link rel="prev" href="' . $url . '" />';
        }

        if ($current < $last) {
            $next = $current + 1;
            $url = $this->generateUrl($next);
            $html .= '<link rel="next" href="' . $url . '" />';
        }

        if ($html) {
            $result .= '<!--amasty_seotoolkit_body' . $html . 'amasty_seotoolkit_body-->';
        }

        return  $result;
    }

    private function generateUrl($page)
    {
        $currentUrl = $this->urlInterface->getCurrentUrl();
        $result = preg_replace('/(\W)p=\d+/', '$1p=' . $page, $currentUrl, -1, $count);

        if (!$count) {
            $delimiter = (strpos($currentUrl, '?') === false) ? '?' : '&';
            $result .= $delimiter . 'p=' . $page;
        }

        return $result;
    }

    /**
     * Remove ?p=1 param from url
     * @param NativePager $subject
     * @param $result
     * @return string
     */
    public function afterGetPageUrl(
        NativePager $subject,
        $result
    ) {
        $this->removeFirstPageParam($result);

        return $result;
    }

    private function removeFirstPageParam(&$url)
    {
        /* check if url not ?p=10*/
        if (strpos($url, 'p=1&') !== false
            || strlen($url) - stripos($url, 'p=1')  === strlen('p=1')//in the end of line
        ) {
            $url = $this->urlHelper->removeRequestParam($url, 'p');
        }
    }
}
