<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_SeoToolKit
 */


namespace Amasty\SeoToolKit\Observer;

use Magento\Framework\Event\ObserverInterface;

class MoveContentToHead implements ObserverInterface
{
    const PLACEHOLDER_IN_HEAD = '<!--amasty_seotoolkit_head--!>';
    const TEXT_IN_BODY = '<!--amasty_seotoolkit_body';
    const TEXT_IN_BODY_END = 'amasty_seotoolkit_body-->';

    /**
     * @var \Amasty\SeoToolKit\Helper\Config
     */
    private $config;

    public function __construct(\Amasty\SeoToolKit\Helper\Config $config)
    {
        $this->config = $config;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $response = $observer->getResponse();

        if ($response) {
            $content = $response->getContent();
            $this->moveLinkBlock($content);
            $observer->getResponse()->setContent($content);
        }
    }

    private function moveLinkBlock(&$content)
    {
        if (strpos($content, self::PLACEHOLDER_IN_HEAD) !== false
            && strpos($content, self::TEXT_IN_BODY) !== false
            && $this->config->isPrevNextLinkEnabled()
        ) {
            $posStart  = strpos($content, self::TEXT_IN_BODY);
            $posEnd    = strpos($content, self::TEXT_IN_BODY_END);
            $links = substr($content, $posStart, $posEnd - $posStart + strlen(self::TEXT_IN_BODY_END));

            $content = str_replace($links, '', $content);
            $links = str_replace(self::TEXT_IN_BODY, '', $links);
            $links = str_replace(self::TEXT_IN_BODY_END, '', $links);

            /* move prev next links to head*/
            $content = str_replace(self::PLACEHOLDER_IN_HEAD, $links, $content);
        } else {
            $content = str_replace(self::PLACEHOLDER_IN_HEAD, '', $content);
        }

        return $content;
    }
}
