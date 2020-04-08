<?php

namespace Codilar\CategoryApi\Model\Pool;

class CategoryProductsAttributesPool
{
    /**
     * @var array
     */
    private $categoryProductsAttributes;

    /**
     * CategoryProductsAttributesPool constructor.
     * @param array $categoryProductsAttributes
     */
    public function __construct(
        array $categoryProductsAttributes = []
    ) {
        $this->categoryProductsAttributes = $categoryProductsAttributes;
    }

    /**
     * @return array
     */
    public function getCategoryProductsAttributes(): array
    {
        return $this->categoryProductsAttributes;
    }
}
