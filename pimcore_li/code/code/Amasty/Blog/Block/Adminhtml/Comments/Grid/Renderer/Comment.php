<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Comments\Grid\Renderer;
use Magento\Framework\DataObject;

class Comment extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    public function render(DataObject $row)
    {
        $message = $row->getData('message');


        $max_length = 340;

        if (strlen($message) > $max_length)
        {
            $offset = ($max_length - 3) - strlen($message);
            $message = substr($message, 0, strrpos($message, ' ', $offset)) . '...';
        }
        $editUrl = __(
            ' <a href="%1">Show more</a>',
            $this->getUrl('*/*/edit', ['id' => $row->getData('comment_id')])
        );

        return $message.$editUrl;
    }

}