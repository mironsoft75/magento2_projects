<?php


namespace Codilar\CategoryApi\Model\CategoryPage\Details;


use Codilar\CategoryApi\Api\CategoryPage\Details\AdditionalAttributeManagementInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterfaceFactory;
use Codilar\CategoryApi\Helper\CategoryHelper;
use Codilar\CategoryApi\Model\Pool\CategoryProductsAttributesPool;

class AdditionalAttributeManagement implements AdditionalAttributeManagementInterface
{
    /**
     * @var CategoryProductsAttributesPool
     */
    private $categoryProductsAttributesPool;
    /**
     * @var AdditionalAttributesInterfaceFactory
     */
    private $additionalAttributesInterfaceFactory;
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;

    /**
     * AdditionalAttributeManagement constructor.
     * @param CategoryProductsAttributesPool $categoryProductsAttributesPool
     * @param AdditionalAttributesInterfaceFactory $additionalAttributesInterfaceFactory
     * @param CategoryHelper $categoryHelper
     */
    public function __construct(
        CategoryProductsAttributesPool $categoryProductsAttributesPool,
        AdditionalAttributesInterfaceFactory $additionalAttributesInterfaceFactory,
        CategoryHelper $categoryHelper
    )
    {
        $this->categoryProductsAttributesPool = $categoryProductsAttributesPool;
        $this->additionalAttributesInterfaceFactory = $additionalAttributesInterfaceFactory;
        $this->categoryHelper = $categoryHelper;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface[]
     */
    public function getProductsAdditionalAttribiutesData($product)
    {
        $allAttributes = $this->categoryProductsAttributesPool->getCategoryProductsAttributes();
        $attributesArray = [];
        foreach ($allAttributes as $attribute) {
            /** @var \Codilar\CategoryApi\Api\Data\CategoryPage\Details\AdditionalAttributesInterface $attributeInterface */
            $attributeInterface = $this->additionalAttributesInterfaceFactory->create();
            $attributeData = $this->categoryHelper->getAttribute($attribute);
            $attributeInterface->setAttributeId($attributeData->getAttributeId())
                ->setAttributeName($attributeData->getDefaultFrontendLabel())
                ->setAttributeValue($product->getData($attribute))
                ->setAttributeValueLabel($this->categoryHelper->getOptionsValueLabel($product->getData($attribute)));
            $attributesArray[] = $attributeInterface;
        }
        return $attributesArray;
    }
}