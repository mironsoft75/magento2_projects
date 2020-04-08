<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Index;
use Magento\Framework\App\Action;

class Search extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Registry $registry
    ) {
        parent::__construct($context);
        $this->registry = $registry;
    }

    public function execute()
    {
        if ($q = $this->getRequest()->getParam('query')) {
            $this->registry->register(\Amasty\Blog\Model\Posts::SEARCH_QUERY_KEY, $q, true);
        }
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
