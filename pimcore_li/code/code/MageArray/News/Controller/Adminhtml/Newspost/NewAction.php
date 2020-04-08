<?php

namespace MageArray\News\Controller\Adminhtml\Newspost;

use MageArray\News\Controller\Adminhtml\Newspost;

/**
 * Class NewAction
 * @package MageArray\News\Controller\Adminhtml\Newspost
 */
class NewAction extends Newspost
{
    /**
     *
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
