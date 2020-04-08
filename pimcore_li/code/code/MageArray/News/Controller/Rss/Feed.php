<?php

namespace MageArray\News\Controller\Rss;

use Magento\Framework\App\Action\Action;

/**
 * Class Feed
 * @package MageArray\News\Controller\Rss
 */
class Feed extends Action
{
    /**
     *
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->getResponse()
            ->setHeader('Content-type', 'text/xml; charset=UTF-8')
            ->setBody($this->_view->getLayout()
                ->getBlock('magearray_news.rss.feed')->toHtml());
    }
}
