<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Controller\Index;

class Rss extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Amasty\Blog\Helper\Url
     */
    protected $urlHelper;
    /**
     * @var \Amasty\Blog\Helper\Settings
     */
    protected $settings;
    /**
     * @var \Magento\Framework\App\ViewInterface
     */
    protected $_view;
    /**
     * @var $rss \Magento\Rss\Model\RssFactory
     */
    protected $rssFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Rss\Model\RssFactory $rssFactory,
        \Amasty\Blog\Helper\Settings $settings,
        \Amasty\Blog\Helper\Url $urlHelper
    ) {
        parent::__construct($context);
        $this->rssFactory = $rssFactory;
        $this->settings   = $settings;
        $this->urlHelper  = $urlHelper;
        $this->_view      = $context->getView();
    }

    public function execute()
    {
        if (!$this->settings->getRssComment($this->getRequest()->getParam('store_id'))) {
            return;
        }

        $params = [];
        if ($this->getRequest()->getParam("post_id")) {
            $params["post_id"] = $this->getRequest()->getParam("post_id");
        }

        $this->getResponse()
             ->setHeader('Content-Type', 'text/xml')
             ->setBody($this->_getContent($params));
    }

    protected function _getContent($params = [])
    {
        $block = $this->_view->getLayout()->createBlock('Amasty\Blog\Block\Rss\Feeds');
        if ($block) {
            $currentUrl = $this->urlHelper->getUrl();

            $data = [
                'title'    => 'Blog Comments',
                'link'     => $currentUrl,
                'charset'  => 'UTF-8'
            ];

            $data['entries'] = $block->getCommentsFeed($params['post_id']);

            \Zend_Feed::importArray($data, 'rss');
            $rssFeedFromArray = \Zend_Feed::importArray($data, 'rss');
            $xml = $rssFeedFromArray->saveXML();
            $rss = new \SimpleXMLElement($xml);

            /** @var SimpleXMLElement $channel */
            $channel = $rss->channel;

            $placeholderContent = "__placeholder__";
            $atomLink = $channel->addChild($placeholderContent);

            $atomLink->addAttribute('href', $currentUrl);
            $atomLink->addAttribute('rel', 'self');
            $atomLink->addAttribute('type', 'application/rss+xml');

            $xml = $rss->asXML();
            $xml = str_replace($placeholderContent, "atom:link", $xml);
            $xml = str_replace('<rss ', '<rss  xmlns:atom="http://www.w3.org/2005/Atom"  ', $xml);

            return $xml;
        }
        return false;
    }
}
