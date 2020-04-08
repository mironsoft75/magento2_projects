<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 17/7/19
 * Time: 4:08 PM
 */

namespace Codilar\Videostore\Plugin\Model;
/**
 * Class Config
 *
 * @package Codilar\Videostore\Plugin\Model
 */
class Config
{
    /**
     * Adding custom options and changing labels
     *
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param [] $options
     * @return []
     */
    public function afterGetAttributeUsedForSortByArray(\Magento\Catalog\Model\Config $catalogConfig, $options)
    {
        unset($options['position']);
        unset($options['name']);
        unset($options['price']);
        $customOption['newest'] = __('Newest Arrival');
        $options = array_merge($customOption, $options);
        $options['low_to_high'] = __('Price - Low To High');
        $options['high_to_low'] = __('Price - High To Low');
        return $options;
    }
}