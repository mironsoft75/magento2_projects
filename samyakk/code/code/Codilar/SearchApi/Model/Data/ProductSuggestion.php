<?php

namespace Codilar\SearchApi\Model\Data;

use Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface;
use Codilar\SearchApi\Api\Data\ProductSuggestionInterface;
use Magento\Framework\DataObject;

class ProductSuggestion extends DataObject implements ProductSuggestionInterface
{

    /**
     * @return \Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface[]
     */
    public function getProductSuggestion()
    {
        return $this->getData('products');
    }

    /**
     * @param \Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface[]
     * @return $this
     */
    public function setProductSuggestion($products)
    {
        return $this->setData('products', $products);
    }

    /**
     * @return integer
     */
    public function getTotal()
    {
        return $this->getData('total');
    }

    /**
     * @param integer $count
     * @return $this
     */
    public function setTotal($count)
    {
        return $this->setData('total', $count);
    }
}
