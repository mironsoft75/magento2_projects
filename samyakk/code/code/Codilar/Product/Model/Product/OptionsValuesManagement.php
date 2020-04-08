<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product;


use Codilar\Product\Api\Data\Product\OptionValuesInterface;
use Codilar\Product\Api\Data\Product\OptionValuesInterfaceFactory;
use Codilar\Product\Api\Product\OptionsValues\ValuesManagementInterface;
use Codilar\Product\Api\Product\OptionsValuesManagementInterface;
use Codilar\Product\Helper\ProductHelper;

class OptionsValuesManagement implements OptionsValuesManagementInterface
{
    /**
     * @var OptionValuesInterfaceFactory
     */
    private $optionValuesInterfaceFactory;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var ValuesManagementInterface
     */
    private $valuesManagement;

    /**
     * OptionsValuesManagement constructor.
     * @param OptionValuesInterfaceFactory $optionValuesInterfaceFactory
     * @param ProductHelper $productHelper
     * @param ValuesManagementInterface $valuesManagement
     */
    public function __construct(
        OptionValuesInterfaceFactory $optionValuesInterfaceFactory,
        ProductHelper $productHelper,
        ValuesManagementInterface $valuesManagement
    )
    {
        $this->optionValuesInterfaceFactory = $optionValuesInterfaceFactory;
        $this->productHelper = $productHelper;
        $this->valuesManagement = $valuesManagement;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\OptionValuesInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getOptionsValue($product)
    {
        $optionArray = [];
        if ($product->getTypeId() == "configurable") {
            $configOptions = $product->getTypeInstance()->getConfigurableOptions($product);
            foreach ($configOptions as &$item) {
                $item = $this->filterUniqueValues($item, 'value_index');
            }

            foreach ($configOptions as $configOptionId => $configOption) {

                $attribute = $this->productHelper->getAttribute($configOptionId);

                /** @var \Codilar\Product\Api\Data\Product\OptionValuesInterface $options */
                $options = $this->optionValuesInterfaceFactory->create();

                $options->setId($attribute->getAttributeId());
                $options->setCode($attribute->getAttributeCode());
                $options->setLabel($attribute->getStoreLabel());
                $options->setType($attribute->getFrontendInput());
                $options->setValues($this->valuesManagement->getOptionValues($configOption));

                $optionArray[] = $options;

            }
        }
        return $optionArray;
    }

    protected function filterUniqueValues($array, $key) {
        $newArray = [];
        $addedValues = [];
        foreach ($array as $item) {
            if (!in_array($item[$key], $addedValues)) {
                $addedValues[] = $item[$key];
                $newArray[] = $item;
            }
        }
        return $newArray;
    }
}