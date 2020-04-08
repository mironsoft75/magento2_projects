<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sms\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CountryCode implements ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [['value' => "+91", 'label' => __('India')], ['value' => "+960", 'label' => __('Maldives')]];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return ["+91" => __('India'), "+960" => __('Maldives')];
    }
}