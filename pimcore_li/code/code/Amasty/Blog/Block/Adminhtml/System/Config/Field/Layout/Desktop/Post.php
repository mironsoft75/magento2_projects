<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\System\Config\Field\Layout\Desktop;

class Post
    extends \Amasty\Blog\Block\Adminhtml\System\Config\Field\Layout\Desktop
{
    protected function _getContentBlocks()
    {
        $result = parent::_getContentBlocks();

        # Add some extra staff
        $result[] = array(
            'value' => 'post',
            'label' => __("Post"),
            'backend_image' => 'images/layout/assets/post.png',
        );
        return $result;
    }
}