<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Form;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Block\Form;

class Renderer extends Form
{
    /**
     * @param ElementInterface $element
     * @return \Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getRenderer(ElementInterface $element)
    {
        $renderer = Form\Renderer\Text::class;
        $data = [
            'element' => $element
        ];
        switch ($element->getType()) {
            case ElementInterface::TYPE_MULTISELECT:
                $data['is_multiple'] = true;
                $renderer = Form\Renderer\Select::class;
                break;
            case ElementInterface::TYPE_SELECT:
                $data['is_multiple'] = false;
                $renderer = Form\Renderer\Select::class;
                break;
            case ElementInterface::TYPE_TEXTAREA:
                $renderer = Form\Renderer\TextArea::class;
                break;
            case ElementInterface::TYPE_DATE:
                $renderer = Form\Renderer\Date::class;
                break;
            case ElementInterface::TYPE_RADIO:
                $renderer = Form\Renderer\Radio::class;
                break;
            case ElementInterface::TYPE_CUSTOM:
                $renderer = Form\Renderer\CustomHtml::class;
                break;
            case ElementInterface::TYPE_EMAIL:
                $data['type'] = "email";
                break;
            case ElementInterface::TYPE_PASSWORD:
                $data['type'] = "password";
                break;
            case ElementInterface::TYPE_CHECKBOX:
                $data['type'] = "checkbox";
                break;
            case ElementInterface::TYPE_HIDDEN:
                $data['type'] = "hidden";
                break;
            case ElementInterface::TYPE_FILE:
                $data['type'] = "file";
                break;
            default:
                $data['type'] = "text";
        }
        return $this->getLayout()->createBlock($renderer)->setData($data);
    }
}