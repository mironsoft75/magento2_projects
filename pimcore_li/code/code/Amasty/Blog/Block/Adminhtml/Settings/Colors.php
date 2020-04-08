<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\Settings;

class Colors extends \Magento\Config\Block\System\Config\Form\Field
{
    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement  $element)
    {
        $render = $this->getLayout()->createBlock('Amasty\Blog\Block\Adminhtml\System\Config\Form\Element\Colors\Render');
        if ($render){
            return $render->toHtml();
        }
        return false;
    }
}

