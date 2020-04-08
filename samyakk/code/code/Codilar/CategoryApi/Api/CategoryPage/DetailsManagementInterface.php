<?php

namespace Codilar\CategoryApi\Api\CategoryPage;

interface DetailsManagementInterface
{
    /**
     * @param array $products
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
     */
    public function getProductsDetails($products);
}
