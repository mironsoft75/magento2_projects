<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Controller\Index;
use Magento\Framework\App\Action;

class Index  extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
