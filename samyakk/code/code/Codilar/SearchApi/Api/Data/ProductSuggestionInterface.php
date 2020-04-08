<?php

namespace Codilar\SearchApi\Api\Data;

interface ProductSuggestionInterface
{
    /**
     * @return \Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface[]
     */
    public function getProductSuggestion();

    /**
     * @param \Codilar\SearchApi\Api\Data\ProductSearchSuggestionInterface[] $products
     * @return $this
     */
    public function setProductSuggestion($products);

    /**
     * @return integer
     */
    public function getTotal();

    /**
     * @param integer $count
     * @return $this
     */
    public function setTotal($count);
}
