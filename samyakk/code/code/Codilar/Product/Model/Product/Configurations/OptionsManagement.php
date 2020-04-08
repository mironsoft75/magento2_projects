<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product\Configurations;


use Codilar\Product\Api\Data\Product\Configurations\OptionsInterface;
use Codilar\Product\Api\Data\Product\Configurations\OptionsInterfaceFactory;
use Codilar\Product\Api\Product\Configurations\OptionsManagementInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class OptionsManagement implements OptionsManagementInterface
{
    /**
     * @var OptionsInterfaceFactory
     */
    private $optionsInterfaceFactory;

    /**
     * @var array
     */
    private $configOptions;
    /**
     * @var Configurable
     */
    private $configurable;

    /**
     * OptionsManagement constructor.
     * @param OptionsInterfaceFactory $optionsInterfaceFactory
     * @param Configurable $configurable
     */
    public function __construct(
        OptionsInterfaceFactory $optionsInterfaceFactory,
        Configurable $configurable
    )
    {
        $this->optionsInterfaceFactory = $optionsInterfaceFactory;
        $this->configurable = $configurable;
        $this->configOptions = [];
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Magento\Catalog\Api\Data\ProductInterface $childProduct
     * @return \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface[]
     */
    public function getOptions($product, $childProduct)
    {
        $data = [];
        $configOptions = $this->getConfigOptions($product);
        foreach ($configOptions as $option) {
            /** @var \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface $options */
            $options = $this->optionsInterfaceFactory->create();
            /**
             * @var \Magento\Catalog\Model\Product $childProduct
             */
            $customAttribute = $childProduct->getCustomAttribute($option['attribute_code']);
            $options->setOptionId($option['attribute_id']);
            $options->setOptionCode($option['attribute_code']);
            $options->setOptionValue($customAttribute->getValue());
            $data[] = $options;
        }
        return $data;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return mixed
     */
    protected function getConfigOptions($product) {
        if (!array_key_exists($product->getId(), $this->configOptions)) {
            $this->configOptions[$product->getId()] = $this->configurable->getConfigurableAttributesAsArray($product);
        }
        return $this->configOptions[$product->getId()];
    }
}