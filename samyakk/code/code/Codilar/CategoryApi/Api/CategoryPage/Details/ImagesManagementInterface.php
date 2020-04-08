<?php

namespace Codilar\CategoryApi\Api\CategoryPage\Details;

interface ImagesManagementInterface
{
    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface[]
     */
    public function getImagesData($product);
}
