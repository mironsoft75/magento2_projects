<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Blog
 */

namespace Amasty\Blog\Block\Adminhtml\System\Config\Field;

class Layout
    extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * @var \Amasty\Blog\Helper\Data
     */
    private $helperData;
    /**
     * @var \Amasty\Blog\Helper\Data\Layout
     */
    private $helperLayout;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Amasty\Blog\Helper\Data $helperData,
        \Amasty\Blog\Helper\Data\Layout $helperLayout,
        array $data =[]
    ) {
        parent::__construct($context, $data);
        $this->helperData = $helperData;
        $this->helperLayout = $helperLayout;
    }

    protected function _helper()
    {
        return $this->helperData;
    }

    protected function _getSidebarBlocks()
    {
        return $this
            ->helperLayout
            ->getBlocks('sidebar');
    }

    protected function _getContentBlocks()
    {
        return $this
            ->helperLayout
            ->getBlocks('content');
    }

    protected function _getLayouts()
    {
        $config = [
            ['value' => 'one-column', 'label' => __("One Column")],
            ['value' => 'two-columns-left', 'label' => __("Two Columns and Left Sidebar")],
            ['value' => 'two-columns-right', 'label' => __("Two Columns and Right Sidebar")],
            ['value' => 'three-columns', 'label' => __("Three Columns")],
        ];

        return $config;
    }

    protected function _wrapSkinImages(array $blocks)
    {
        $data = array();
        foreach ($blocks as $block) {

            if (isset($block['backend_image'])){

                $backendImage = $block['backend_image'];
                //$backendImage = $this->getSkinUrl($backendImage);

                $backendImage = $this->getViewFileUrl('Amasty_Blog/'.$backendImage);


                $block['backend_image'] = $backendImage;
            }
            $data[] = $block;
        }

        return $data;
    }

    public function getLayoutConfig()
    {
        $contentBlocks = $this->_getContentBlocks();
        $sidebarBlocks = $this->_getSidebarBlocks();

        return array(
            'content' => $this->_wrapSkinImages($contentBlocks),
            'sidebar' => $this->_wrapSkinImages($sidebarBlocks),
            'layouts' => $this->_getLayouts(),
            'delete_message' => __("Are you sure?"),
        );
    }

    protected function _getElementHtml(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $renderer = $this
            ->getLayout()
            ->createBlock('Amasty\Blog\Block\Adminhtml\System\Config\Field\Layout\Renderer')
        ;

        if ($renderer) {
            $renderer
                ->setElementId($element->getHtmlId())
                ->setElementName($element->getName())
                ->setElementValue($element->getValue())
                ->setLayoutConfig($this->getLayoutConfig());

            return $renderer->toHtml();
        }

        return false;
    }

    public function render(\Magento\Framework\Data\Form\Element\AbstractElement $element)
    {
        $id = $element->getHtmlId();

        $html = "<td colspan=\"5\">";

        // replace [value] with [inherit]
        $namePrefix = preg_replace('#\[value\](\[\])?$#', '', $element->getName());

        $html .= "<div class=\"label\">";
        $html .= $element->getLabel();
        $html .= "</div>";

        $addInheritCheckbox = false;
        if ($element->getCanUseWebsiteValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = __('Use Website');
        } elseif ($element->getCanUseDefaultValue()) {
            $addInheritCheckbox = true;
            $checkboxLabel = __('Use Default');
        }

        if ($addInheritCheckbox) {
            $inherit = $element->getInherit() == 1 ? 'checked="checked"' : '';
            if ($inherit) {
                $element->setDisabled(true);
            }
        }

        $html .= '<div class="scope-label">';
        if ($element->getScope()) {
            $html .= $element->getScopeLabel();
        }
        $html .= '</div>';


        if ($addInheritCheckbox) {

            $defText = $element->getDefaultValue();

            // default value
            $html .= '<div class="use-default">';
            $html .= '<input id="' . $id . '_inherit" name="'
                . $namePrefix . '[inherit]" type="checkbox" value="1" class="checkbox config-inherit" '
                . $inherit . ' " /> ';
            $html .= '<label for="' . $id . '_inherit" class="inherit" title="'
                . htmlspecialchars($defText) . '">' . $checkboxLabel . '</label>';
            $html .= '</div>';
        }


        $html .= "<div class=\"fixed\"></div>";

        $html .= "<div class=\"layout-element\">";
        $html .= $this->_getElementHtml($element);
        $html .= "</div>";


        $html .= "</td>";

        return '<tr id="row_' . $element->getHtmlId() . '">' . $html . '</tr>';
    }


}