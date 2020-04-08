<?php
/**
 * SocialShare
 *
 * @package     Ulmod_SocialShare
 * @author      Ulmod <support@ulmod.com>
 * @copyright   Copyright (c) 2016 Ulmod (http://www.ulmod.com/)
 * @license     http://www.ulmod.com/license-agreement.html
 */
 
namespace Ulmod\SocialShare\Block\Adminhtml\System\Config;

use Magento\Config\Block\System\Config\Form\Field as FormField;
use Magento\Framework\Registry;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Color extends FormField
{
    /**
     * @var Registry
     */
    protected $coreRegistry;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        array $data = []
    ) {
         $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $base = $this->getBaseUrl();
        $html = $element->getElementHtml();
        $jsPath = $this->getViewFileUrl('Ulmod_SocialShare::js/jscolor.js');
        $imgPath = $this->getViewFileUrl('Ulmod_SocialShare::js/color.png');
        if (!$this->coreRegistry->registry('colorpicker_loaded')) {
            $html .= '<script type="text/javascript" src="'. $jsPath .'">
                </script>
                 <style type="text/css">
                 input.jscolor { background-image: url('.$imgPath.') !important;
                background-position: calc(100% - 8px) center; 
                background-repeat: no-repeat; padding-right: 44px !important; }
                input.jscolor.disabled,input.jscolor[disabled] { pointer-events: none; }
                </style>';
            $this->coreRegistry->registry('colorpicker_loaded', 1);
        }
        $html .= '<script type="text/javascript">
                var el = document.getElementById("'. $element->getHtmlId() .'");
                el.className = el.className + " jscolor";
            </script>';
        return $html;
    }
}