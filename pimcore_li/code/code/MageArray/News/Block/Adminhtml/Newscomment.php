<?php

namespace MageArray\News\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Newscomment
 * @package MageArray\News\Block\Adminhtml
 */
class Newscomment extends Container
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_newscomment';
        $this->_blockGroup = 'MageArray_News';
        $this->_headerText = __('Manage News Comments');
        parent::_construct();
        $this->removeButton('add');
    }
}
