<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 24/7/19
 * Time: 11:09 AM
 */

namespace Codilar\RelatedProducts\Model\Data;

use Codilar\RelatedProducts\Api\Data\RelatedProductInterface;

class RelatedProduct extends\Magento\Framework\DataObject implements RelatedProductInterface
{
    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
     */
    public function getProduct()
    {
        return $this->getData('product');
    }

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[] $product
     * @return $this
     */
    public function setProduct($product)
    {
        return $this->setData('product', $product);
    }
}
