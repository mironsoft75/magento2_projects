<?php

namespace MageArray\News\Controller\Adminhtml\Newscat;

use MageArray\News\Controller\Adminhtml\Newscat;

/**
 * Class NewAction
 * @package MageArray\News\Controller\Adminhtml\Newscat
 */
class NewAction extends Newscat
{
    /**
     *
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
