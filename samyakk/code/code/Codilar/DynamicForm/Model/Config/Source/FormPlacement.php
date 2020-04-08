<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Config\Source;


use Magento\Framework\Data\OptionSourceInterface;

class FormPlacement implements OptionSourceInterface
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
    
    public function toArray()
    {
        return [
            \Codilar\DynamicForm\Model\Data\Form::FORM_PLACEMENT_TOP => __("Top"),
            \Codilar\DynamicForm\Model\Data\Form::FORM_PLACEMENT_BOTTOM => __("Bottom"),
            \Codilar\DynamicForm\Model\Data\Form::FORM_PLACEMENT_LEFT => __("Left"),
            \Codilar\DynamicForm\Model\Data\Form::FORM_PLACEMENT_RIGHT => __("Right"),
            \Codilar\DynamicForm\Model\Data\Form::FORM_PLACEMENT_WRAP => __("Wrap (use {{var form}} anywhere inside the content)")
        ];
    }
}