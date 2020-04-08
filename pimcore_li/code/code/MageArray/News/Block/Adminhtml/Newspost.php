<?php

namespace MageArray\News\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Newspost
 * @package MageArray\News\Block\Adminhtml
 */
class Newspost extends Container
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_newspost';
        $this->_blockGroup = 'MageArray_News';
        $this->_headerText = __('Manage News Post');
        $this->_addButtonLabel = __('Add New Post');
        parent::_construct();
    }
}
