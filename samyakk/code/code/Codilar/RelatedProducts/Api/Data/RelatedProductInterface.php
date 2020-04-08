<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 24/7/19
 * Time: 11:06 AM
 */

namespace Codilar\RelatedProducts\Api\Data;

interface RelatedProductInterface
{

    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
     */
    public function getProduct();

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[] $product
     * @return $this
     */
    public function setProduct($product);
}
