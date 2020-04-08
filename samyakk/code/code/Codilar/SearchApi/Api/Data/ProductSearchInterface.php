<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 1:40 PM
 */

namespace Codilar\SearchApi\Api\Data;

interface ProductSearchInterface
{
    /**
     * @return \Codilar\SearchApi\Api\Data\SearchSuggestion\SearchDataInterface[]
     */
    public function getProduct();

    /**
     * @param \Codilar\SearchApi\Api\Data\SearchSuggestion\SearchDataInterface[] $product
     * @return $this
     */
    public function setProduct($product);

}
