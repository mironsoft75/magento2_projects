<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Model\Source;


use Codilar\Offers\Api\Data\HomepageBlocksInterface;
use Magento\Framework\Data\OptionSourceInterface;

class DisplayType implements OptionSourceInterface
{

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [
        [
            'value' => HomepageBlocksInterface::DISPLAY_TYPE_CAROUSEL,
            'label' => HomepageBlocksInterface::DISPLAY_TYPE_CAROUSEL_LABEL
        ],
        [
            'value' => HomepageBlocksInterface::DISPLAY_TYPE_GRID,
            'label' => HomepageBlocksInterface::DISPLAY_TYPE_GRID_LABEL
        ]
    ];
        return $options;
    }

}