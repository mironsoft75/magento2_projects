<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Config\Source;


use Magento\Framework\Data\OptionSourceInterface;

class EmailSender implements OptionSourceInterface
{

    const GENERAL = "general";
    const SALES = "sales";
    const SUPPORT = "support";
    const CUSTOM1 = "custom1";
    const CUSTOM2 = "custom2";

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
                'label' => $label,
                'value' => $value
            ];
        }
        return $response;
    }

    public function toArray()
    {
        return [
            self::GENERAL   =>  __("General Contact"),
            self::SALES     =>  __("Sales Representative"),
            self::SUPPORT   =>  __("Customer Support"),
            self::CUSTOM1   =>  __("Custom Email 1"),
            self::CUSTOM2   =>  __("Custom Email 2")
        ];
    }
}