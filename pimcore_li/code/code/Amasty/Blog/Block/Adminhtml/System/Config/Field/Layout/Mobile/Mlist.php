<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\System\Config\Field\Layout\Mobile;

class Mlist
    extends \Amasty\Blog\Block\Adminhtml\System\Config\Field\Layout\Mobile
{
    protected function _getContentBlocks()
    {
        $result = parent::_getContentBlocks();
        # Add some extra staff
        $result[] = array(
            'value' => 'list',
            'label' => (string)__("List"),
            'backend_image' => 'images/layout/assets/list_list.png',

        );
        $result[] = array(
            'value' => 'grid',
            'label' => (string)__("Grid"),
            'backend_image' => 'images/layout/assets/list_grid.png',
        );
        return $result;
    }
}