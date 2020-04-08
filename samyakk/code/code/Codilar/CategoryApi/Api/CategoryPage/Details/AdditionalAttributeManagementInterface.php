<?php

namespace Codilar\CategoryApi\Api\CategoryPage\Details;

interface AdditionalAttributeManagementInterface
{
    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface[]
     */
    public function getProductsAdditionalAttribiutesData($product);
}
