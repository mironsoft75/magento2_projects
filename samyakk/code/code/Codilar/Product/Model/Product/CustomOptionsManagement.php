<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product;


use Codilar\Product\Api\Data\Product\CustomOptionsInterface;
use Codilar\Product\Api\Data\Product\CustomOptionsInterfaceFactory;
use Codilar\Product\Api\Product\CustomOptions\ValuesManagementInterface;
use Codilar\Product\Api\Product\CustomOptionsManagementInterface;
use Magento\Catalog\Model\Product\Option as ProductCustomOptions;

class CustomOptionsManagement implements CustomOptionsManagementInterface
{
    /**
     * @var ProductCustomOptions
     */
    private $productCustomOptions;
    /**
     * @var CustomOptionsInterfaceFactory
     */
    private $customOptionsInterfaceFactory;
    /**
     * @var ValuesManagementInterface
     */
    private $valuesManagement;

    /**
     * CustomOptionsManagement constructor.
     * @param ProductCustomOptions $productCustomOptions
     * @param CustomOptionsInterfaceFactory $customOptionsInterfaceFactory
     * @param ValuesManagementInterface $valuesManagement
     */
    public function __construct(
        ProductCustomOptions $productCustomOptions,
        CustomOptionsInterfaceFactory $customOptionsInterfaceFactory,
        ValuesManagementInterface $valuesManagement
    )
    {
        $this->productCustomOptions = $productCustomOptions;
        $this->customOptionsInterfaceFactory = $customOptionsInterfaceFactory;
        $this->valuesManagement = $valuesManagement;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\CustomOptionsInterface[]
     */
    public function getProductCustomOptions($product)
    {
        $customOptions = $this->productCustomOptions->getProductOptionCollection($product);
        $options = [];
        foreach ($customOptions as $customOption) {
            /** @var \Codilar\Product\Api\Data\Product\CustomOptionsInterface $option */
            $option = $this->customOptionsInterfaceFactory->create();
            $option->setId($customOption->getOptionId())
                ->setLabel($customOption->getDefaultTitle())
                ->setType($customOption->getType())
                ->setRequired($customOption->getIsRequire())
                ->setValues($this->valuesManagement->getCustomOptionsValues($customOption->getValues()));
            $options[] = $option;
        }
        return $options;
    }
}