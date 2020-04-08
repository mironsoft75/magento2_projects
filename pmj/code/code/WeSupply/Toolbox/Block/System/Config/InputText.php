<?php
namespace WeSupply\Toolbox\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use WeSupply\Toolbox\Helper\Data;

class InputText extends Field
{
    /**
     * @param \Magento\Framework\Data\Form\Element\AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        return parent::_getElementHtml($element).'<span>.'.Data::WESUPPLY_DOMAIN.'</span>';
    }

}