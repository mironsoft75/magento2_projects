<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Plugin\Quote\Item;

use Magento\Quote\Api\Data\CartItemInterface;
use Magento\Quote\Model\Quote\Item\CartItemOptionsProcessor as Subject;
use Magento\Quote\Model\Quote\ProductOption;
use Magento\Quote\Model\Quote\ProductOptionFactory;

class CartItemOptionsProcessor
{
    /**
     * @var ProductOptionFactory
     */
    private $productOptionFactory;

    /**
     * CartItemOptionsProcessor constructor.
     * @param ProductOptionFactory $productOptionFactory
     */
    public function __construct(
        ProductOptionFactory $productOptionFactory
    )
    {
        $this->productOptionFactory = $productOptionFactory;
    }

    /**
     * @param Subject $subject
     * @param CartItemInterface $cartItem
     * @return CartItemInterface
     */
    public function afterApplyCustomOptions(Subject $subject, CartItemInterface $cartItem)
    {
        /** @var \Magento\Quote\Model\Quote\Item $cartItem */
        $additionalOptions = $cartItem->getOptionByCode('additional_options');
        $additionalOptions = $additionalOptions ? \json_decode($additionalOptions->getValue(), true) : [];
        if (count($additionalOptions) > 0) {
            /** @var ProductOption $productOptions */
            $productOptions = $cartItem->getProductOption();
            if (!$productOptions) {
                $productOptions = $this->productOptionFactory->create();
            }
            $this->mapAdditionalOptionToExtensionAttribute($additionalOptions, $productOptions->getExtensionAttributes());
            $cartItem->setProductOption($productOptions);
        }
        return $cartItem;
    }

    /**
     * @param array $additionalOptions
     * @param \Magento\Quote\Api\Data\ProductOptionExtensionInterface $extensionAttributes
     * @return \Magento\Quote\Api\Data\ProductOptionExtensionInterface
     */
    protected function mapAdditionalOptionToExtensionAttribute($additionalOptions, $extensionAttributes)
    {
        foreach ($additionalOptions as $additionalOption) {
            switch ($additionalOption['label']) {
                case __("Comments")->render():
                    $extensionAttributes->setComments($additionalOption['value']);
                    break;
            }
        }
        return $extensionAttributes;
    }
}