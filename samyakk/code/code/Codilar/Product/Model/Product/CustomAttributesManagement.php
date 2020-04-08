<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product;

use Codilar\Product\Api\Data\Product\AttributesDataInterface;
use Codilar\Product\Api\Data\Product\AttributesDataInterfaceFactory;
use Codilar\Product\Api\Product\CustomAttributesManagementInterface;
use Codilar\Product\Helper\ProductHelper;
use Codilar\Product\Model\Pool\CustomAttributesPool;

class CustomAttributesManagement implements CustomAttributesManagementInterface
{
    /**
     * @var AttributesDataInterfaceFactory
     */
    private $attributesDataInterfaceFactory;
    /**
     * @var CustomAttributesPool
     */
    private $customAttributesPool;
    /**
     * @var ProductHelper
     */
    private $productHelper;

    /**
     * CustomAttributesManagement constructor.
     * @param AttributesDataInterfaceFactory $attributesDataInterfaceFactory
     * @param CustomAttributesPool $customAttributesPool
     * @param ProductHelper $productHelper
     */
    public function __construct(
        AttributesDataInterfaceFactory $attributesDataInterfaceFactory,
        CustomAttributesPool $customAttributesPool,
        ProductHelper $productHelper
    )
    {
        $this->attributesDataInterfaceFactory = $attributesDataInterfaceFactory;
        $this->customAttributesPool = $customAttributesPool;
        $this->productHelper = $productHelper;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getCustomAttributes($product)
    {
        $specifications = $this->getCustomAttributeSet($product);
        $response = [];
        foreach ($specifications as $specification) {
            /** @var \Codilar\Product\Api\Data\Product\AttributesDataInterface $attribute */
            $attribute = $this->attributesDataInterfaceFactory->create();
            $response[] = $attribute->setLabel($specification['label'])
                ->setCode($specification['code'])
                ->setValue($specification['value']);
        }
        return $response;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getCustomAttributeSet($product)
    {
        $specifications = $this->customAttributesPool->getCustomAttributes();
        return $this->productHelper->getAttributesData($specifications, $product);
    }
}