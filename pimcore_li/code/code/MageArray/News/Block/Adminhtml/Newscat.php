<?php

namespace MageArray\News\Block\Adminhtml;

use Magento\Backend\Block\Widget\Grid\Container;

/**
 * Class Newscat
 * @package MageArray\News\Block\Adminhtml
 */
class Newscat extends Container
{
    /**
     *
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_newscat';
        $this->_blockGroup = 'MageArray_News';
        $this->_headerText = __('Manage News Category');
        $this->_addButtonLabel = __('Add New Category');
        parent::_construct();
    }
}
