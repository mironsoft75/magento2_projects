<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Config\Source\Element;


use Magento\Framework\Data\OptionSourceInterface;

class ValidationTypes implements OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $response = [];
        $response[] = [
            'label' =>  __("-- Choose a Validation --"),
            'value' => ''
        ];
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
            'required'  =>  'Required Field',
            'minlength' => "Min Text Length",
            'maxlength' => "Max Text Length",
            'max-words' => "Max Words",
            'min-words' => "Min Words",
            'range-words' => "Range Words",
            'letters-with-basic-punc' => "Letters With Basic Punc",
            'alphanumeric' => "Alphanumeric",
            'letters-only' => "Letters Only",
            'no-whitespace' => "No Whitespace",
            'zip-range' => "Zip Range",
            'integer' => "Integer",
            'vinUS' => "vinUS",
            'dateITA' => "Date ITA",
            'dateNL' => "Date NL",
            'time' => "Time",
            'time12h' => "Time 12h",
            'phoneUS' => "Phone US",
            'phoneUK' => "Phone UK",
            'mobileUK' => "Mobile UK",
            'stripped-min-length' => "Stripped Min Length",
            'email2' => "Email 2",
            'url2' => "URl 2",
            'credit-card-types' => "Credit Card Types",
            'ipv4' => "IPV4",
            'ipv6' => "IPV6",
            'pattern' => "Pattern"
        ];
    }
}