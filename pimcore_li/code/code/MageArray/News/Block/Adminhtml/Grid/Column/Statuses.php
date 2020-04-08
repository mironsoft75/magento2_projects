<?php

namespace MageArray\News\Block\Adminhtml\Grid\Column;

use Magento\Backend\Block\Widget\Grid\Column;

/**
 * Class Statuses
 * @package MageArray\News\Block\Adminhtml\Grid\Column
 */
class Statuses extends Column
{
    /**
     * @return array
     */
    public function getFrameCallback()
    {
        return [$this, 'decorateStatus'];
    }

    /**
     * @param $value
     * @param $row
     * @return string
     */
    public function decorateStatus($value, $row)
    {
        if (
            $row->getIsActive() == 1 ||
            $row->getCatStatus() == 1 ||
            $row->getStatus()
        ) {
            $cell = '<span class="grid-severity-notice"><span>' .
                $value . '</span></span>';
        } else {
            $cell = '<span class="grid-severity-critical"><span>' .
                $value . '</span></span>';
        }
        return $cell;
    }
}
