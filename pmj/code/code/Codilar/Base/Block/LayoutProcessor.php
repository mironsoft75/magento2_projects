<?php
namespace Codilar\Base\Block;

use Magento\Checkout\Block\Checkout\LayoutProcessorInterface;

/**
 * Class LayoutProcessor
 * @package Codilar\Base\Block
 */
class LayoutProcessor implements LayoutProcessorInterface
{
    /**
     * @param array $jsLayout
     * @return array
     */
    public function process($jsLayout)
    {
        if (isset($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
            ['payment']['children']['payments-list']['children']
        )) {
            foreach ($jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                     ['payment']['children']['payments-list']['children'] as $key => $payment) {
                /* country_id */
                if (isset($payment['children']['form-fields']['children']['country_id'])) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
                    ['country_id']['sortOrder'] = 71;
                }
                /* region_id */
                if (isset($payment['children']['form-fields']['children']['region_id'])) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
                    ['region_id']['sortOrder'] = 72;
                }
                /* postcode */
                if (isset($payment['children']['form-fields']['children']['postcode'])) {
                    $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                    ['payment']['children']['payments-list']['children'][$key]['children']['form-fields']['children']
                    ['postcode']['sortOrder'] = 101;
                }
            }
        }
        return $jsLayout;
    }
}