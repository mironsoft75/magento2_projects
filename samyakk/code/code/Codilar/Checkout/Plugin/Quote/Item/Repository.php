<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Plugin\Quote\Item;

use Magento\Quote\Model\Quote\Item\Option;
use Magento\Quote\Model\Quote\Item\OptionFactory;
use Magento\Quote\Model\Quote\Item\Repository as Subject;

class Repository
{
    /**
     * @var OptionFactory
     */
    private $quoteItemOptionFactory;

    /**
     * Repository constructor.
     * @param OptionFactory $quoteItemOptionFactory
     */
    public function __construct(
        OptionFactory $quoteItemOptionFactory
    )
    {
        $this->quoteItemOptionFactory = $quoteItemOptionFactory;
    }

    /**
     * @param Subject $subject
     * @param callable $proceed
     * @param \Magento\Quote\Model\Quote\Item $item
     * @return \Magento\Quote\Model\Quote\Item
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Exception
     */
    public function aroundSave(Subject $subject, callable $proceed, $item)
    {
        $additionalOptions = [];
        if ($additionalOption = $item->getOptionByCode('additional_options')) {
            $additionalOptions = \json_decode($additionalOption->getValue(), true);
        }

        $comments = $item->getProductOption() ? $item->getProductOption()->getExtensionAttributes()->getComments() : null;
        if ($comments) {
            $item->getProductOption()->getExtensionAttributes()->setComments(null);
            $additionalOptions[] = [
                'label' =>  __("Comments")->render(),
                'value' =>  $comments
            ];
        }

        /** @var \Magento\Quote\Model\Quote\Item $item */
        $item = $proceed($item);
        if (count($additionalOptions) > 0) {
            /** @var Option $option */
            $option = $this->quoteItemOptionFactory->create();
            $option->setData([
                'item_id' => $item->getId(),
                'product_id' => $item->getProduct()->getId(),
                'code' => 'additional_options',
                'value' => \json_encode($additionalOptions)
            ]);
            $option->save();
        }
        return $item;
    }
}