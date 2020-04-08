<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Source;


use Magento\Framework\Data\OptionSourceInterface;

class Mode implements OptionSourceInterface
{
    const MODE_LIVE = "1";
    const MODE_TEST = "2";

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return [
            [
                'label' =>  __("Live"),
                'value' =>  self::MODE_LIVE
            ],
            [
                'label' =>  __("Test"),
                'value' =>  self::MODE_TEST
            ]
        ];
    }
}