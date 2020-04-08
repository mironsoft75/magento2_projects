<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Model\Source;


use Magento\Framework\Data\OptionSourceInterface;

class Day implements OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $response = [];
        $v=1;
        foreach ($this->getDays() as $day) {
            $response[] = [
                'label' =>ucwords($day),
                'value' => $v
            ];
            $v= $v+1;
        }
        return $response;
    }

    public function getDays() {
        return [
            'monday',
            'tuesday',
            'wednesday',
            'thursday',
            'friday',
            'saturday',
            'sunday'
        ];
    }
}