<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */


namespace Amasty\Blog\Block\Adminhtml\Posts\Grid\Renderer;

use Magento\Framework\DataObject;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    public function render(DataObject $row)
    {
        $status = $row->getData('status');

        $html = '';
        switch ($status) {
            case \Amasty\Blog\Model\Posts::STATUS_ENABLED:
                $html = '<span style="color:green">'.__('Published').'</span>';
                break;
            case \Amasty\Blog\Model\Posts::STATUS_HIDDEN:
                $html = '<span style="color:grey">'.__('Hidden').'</span>';
                break;
            case \Amasty\Blog\Model\Posts::STATUS_DISABLED:
                $html = '<span style="color:red">'.__('Disabled').'</span>';
                break;
            case \Amasty\Blog\Model\Posts::STATUS_SCHEDULED:
                $html = '<span style="color:grey">'.__('Scheduled').'</span>';
                break;
        }

        return $html;
    }

}