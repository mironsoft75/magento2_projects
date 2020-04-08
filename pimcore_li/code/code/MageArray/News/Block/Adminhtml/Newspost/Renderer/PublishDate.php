<?php
 
namespace MageArray\News\Block\Adminhtml\Newspost\Renderer;
 
use Magento\Framework\DataObject;
class PublishDate extends \Magento\Backend\Block\Widget\Grid\Column\Renderer\AbstractRenderer
{
    /**
     * get category name
     * @param  DataObject $row
     * @return string
     */
    public function render(DataObject $row)
    {
        return date('M, d Y', strtotime($row->getPublishDate())).' - '.$row->getPublishDate();
    }
}