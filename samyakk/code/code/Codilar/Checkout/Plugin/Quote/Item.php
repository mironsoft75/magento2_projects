<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Plugin\Quote;

use Magento\Quote\Model\Quote\Item as Subject;

class Item
{
    /**
     * @param Subject $subject
     * @param \Magento\Catalog\Model\Product $product
     * @return array
     */
    public function beforeRepresentProduct(Subject $subject, $product)
    {
        // TODO: Modify represent product logic to include product comment (New comment should mean new item in cart)
//        $customOptions = $product->getCustomOptions();
//
//        $additionalOptions = [];
//        if ($additionalOption = $subject->getOptionByCode('additional_options')) {
//            $additionalOptions = \json_decode($additionalOption->getValue(), true);
//        }
//        if ($additionalOptions) {
//            foreach ($additionalOptions as $additionalOption) {
//                if ($additionalOption['label'] === __("Comments")->render()) {
//                    $customOptions[] = $additionalOption;
//                }
//            }
//        }
//
//        $product->setCustomOptions($customOptions);
        return [$product];
    }
}