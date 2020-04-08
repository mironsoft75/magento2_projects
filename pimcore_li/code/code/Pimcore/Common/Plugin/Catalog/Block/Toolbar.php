<?php

namespace Pimcore\Common\Plugin\Catalog\Block;

/**
 * Class Toolbar
 * @package Pimcore\Common\Plugin\Catalog\Block
 */
class Toolbar
{
    /**
     * Plugin
     *
     * @param \Magento\Catalog\Block\Product\ProductList\Toolbar $subject
     * @param \Closure $proceed
     * @param \Magento\Framework\Data\Collection $collection
     * @return \Magento\Catalog\Block\Product\ProductList\Toolbar
     */
    public function aroundSetCollection(
        \Magento\Catalog\Block\Product\ProductList\Toolbar $subject,
        \Closure $proceed,
        $collection
    ) {
        $currentOrder = $subject->getCurrentOrder();
        $result = $proceed($collection);

        if ($currentOrder) {
            if ($currentOrder == 'price_desc') {
                $subject->getCollection()->setOrder('price', 'desc');
            } elseif ($currentOrder == 'price_asc') {
                $subject->getCollection()->setOrder('price', 'asc');
            }
            elseif ($currentOrder == 'name_asc') {
                $subject->getCollection()->setOrder('name', 'asc');
            }
            elseif ($currentOrder == 'name_desc') {
                $subject->getCollection()->setOrder('name', 'desc');
            }
            elseif ($currentOrder == 'best_seller') {
                $subject->getCollection()->setOrder('sales_rank', 'asc');
            }
        }

        return $result;
    }
}