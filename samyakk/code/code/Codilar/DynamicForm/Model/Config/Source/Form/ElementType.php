<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Config\Source\Form;


use Magento\Framework\Data\OptionSourceInterface;

class ElementType implements OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $response = [];
        foreach ($this->toArray() as $value => $label) {
            $response[] = [
                'label' =>  $label,
                'value' =>  $value
            ];
        }
        return $response;
    }
    
    public function toArray() {
        return [
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_TEXT => __("Text"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_TEXTAREA => __("Textarea"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_PASSWORD => __("Password"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_EMAIL => __("Email"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_FILE => __("File"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_SELECT => __("Select"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_MULTISELECT => __("Multiselect"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_CHECKBOX => __("Checkbox"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_RADIO => __("Radio"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_DATE => __("Date"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_HIDDEN => __("Hidden"),
            \Codilar\DynamicForm\Api\Data\Form\ElementInterface::TYPE_CUSTOM => __("Custom HTML (Advanced)")
        ];
    }
}