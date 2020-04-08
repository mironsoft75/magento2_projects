<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Comments\Grid\Renderer;

use Magento\Framework\DataObject;

class Status extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\Text
{
    public function render(DataObject $row)
    {
        $status = $row->getData('status');

        $html = '';
        switch ($status) {
            case \Amasty\Blog\Model\Comments::STATUS_APPROVED:
                $html = '<span style="color:green">'.__('Approved').'</span>';
                break;
            case \Amasty\Blog\Model\Comments::STATUS_PENDING:
                $html = '<span style="color:grey">'.__('Pending').'</span>';
                break;
            case \Amasty\Blog\Model\Comments::STATUS_REJECTED:
                $html = '<span style="color:red">'.__('Rejected').'</span>';
                break;
        }

        return $html;
    }

}