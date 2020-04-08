<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 1:44 PM
 */

namespace Codilar\SearchApi\Model\Data;

use Codilar\SearchApi\Api\Data\ProductSearchInterface;

class ProductSearch extends \Magento\Framework\DataObject implements ProductSearchInterface
{
    /**
     * @return \Codilar\SearchApi\Api\Data\SearchSuggestion\SearchDataInterface[]|mixed
     */
    public function getProduct()
    {
        return $this->getData('product');

    }

    /**
     * @param \Codilar\SearchApi\Api\Data\SearchSuggestion\SearchDataInterface[] $product
     * @return ProductSearchInterface|ProductSearch
     */
    public function setProduct($product)
    {
        return $this->setData('product', $product);
    }
}
