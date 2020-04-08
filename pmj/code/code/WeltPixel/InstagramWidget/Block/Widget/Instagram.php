<?php
namespace WeltPixel\InstagramWidget\Block\Widget;

class Instagram extends \Magento\Framework\View\Element\Template implements \Magento\Widget\Block\BlockInterface
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('widget/instagram_widget.phtml');
    }
}
