<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Categories\Grid\Renderer;
use Magento\Framework\DataObject;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    public function render(DataObject $row)
    {
        $status = $row->getData('status');
        $html = '';
        switch ($status) {
            case \Amasty\Blog\Helper\Categories::STATUS_ENABLED:
                $html = '<span style="color:green">'.__('Enabled').'</span>';
                break;
            case \Amasty\Blog\Helper\Categories::STATUS_DISABLED:
                $html = '<span style="color:red">'.__('Disabled').'</span>';
                break;
        }

        return $html;
    }
}