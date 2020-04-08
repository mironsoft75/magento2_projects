<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product;


use Codilar\Product\Api\Product\ConfigurationManagementInterface;
use Codilar\Product\Api\Data\Product\ConfigurationsInterface;
use Codilar\Product\Api\Data\Product\ConfigurationsInterfaceFactory;
use Codilar\Product\Api\Product\Configurations\OptionsManagementInterface;
use Codilar\Product\Helper\ProductHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Codilar\Product\Api\Product\CustomAttributesManagementInterface;
use Codilar\Product\Api\Product\SpecificationManagementInterface;

class ConfigurationManagement implements ConfigurationManagementInterface
{
    /**
     * @var ConfigurationsInterfaceFactory
     */
    private $configurationsInterfaceFactory;
    /**
     * @var Configurable
     */
    private $configurable;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var OptionsManagementInterface
     */
    private $optionsManagement;
    /**
     * @var SpecificationManagementInterface
     */
    private $specificationManagement;
    /**
     * @var CustomAttributesManagementInterface
     */
    private $customAttributesManagement;

    /**
     * ConfigurationManagement constructor.
     * @param ConfigurationsInterfaceFactory $configurationsInterfaceFactory
     * @param Configurable $configurable
     * @param ProductHelper $productHelper
     * @param ProductRepositoryInterface $productRepository
     * @param OptionsManagementInterface $optionsManagement
     * @param SpecificationManagementInterface $specificationManagement
     * @param CustomAttributesManagementInterface $customAttributesManagement
     */
    public function __construct(
        ConfigurationsInterfaceFactory $configurationsInterfaceFactory,
        Configurable $configurable,
        ProductHelper $productHelper,
        ProductRepositoryInterface $productRepository,
        OptionsManagementInterface $optionsManagement,
        SpecificationManagementInterface $specificationManagement,
        CustomAttributesManagementInterface $customAttributesManagement
    )
    {
        $this->configurationsInterfaceFactory = $configurationsInterfaceFactory;
        $this->configurable = $configurable;
        $this->productHelper = $productHelper;
        $this->productRepository = $productRepository;
        $this->optionsManagement = $optionsManagement;
        $this->specificationManagement = $specificationManagement;
        $this->customAttributesManagement = $customAttributesManagement;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\ConfigurationsInterface[]
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductConfiguration($product)
    {
        $configurations = [];
        $simpleProducts = $this->configurable->getUsedProductCollection($product);
        /** @var \Magento\Catalog\Api\Data\ProductInterface $simpleProduct */
        foreach ($simpleProducts as $simpleProduct) {
            /** @var \Magento\Catalog\Api\Data\ProductInterface $childProduct */
            $childProduct = $this->productRepository->getById((int)$simpleProduct->getId());
            /** @var \Codilar\Product\Api\Data\Product\ConfigurationsInterface $configProduct */
            $configProduct = $this->configurationsInterfaceFactory->create();
            $configProduct->setProductId($childProduct->getId())
                ->setProductName($childProduct->getName())
                ->setProductSku($childProduct->getSku())
                ->setPrice($this->productHelper->formatPrice($childProduct->getPrice()))
                ->setSpecialPrice($this->productHelper->formatPrice($childProduct->getSpecialPrice()))
                ->setOfferPercent($this->productHelper->getOfferPercentage($childProduct->getPrice(), $childProduct->getSpecialPrice()))
                ->setMediaGallery($this->productHelper->getProductImagesArray($childProduct))
                ->setLongDescription($childProduct->getDescription() ? $childProduct->getDescription() : "")
                ->setShortDescription($childProduct->getShortDescription() ? $childProduct->getShortDescription() : "")
                ->setStockStatus($this->productHelper->getStockStatus($childProduct))
                ->setOptions($this->optionsManagement->getOptions($product, $childProduct))
                ->setSpecifications($this->specificationManagement->getProductSpecification($childProduct))
                ->setCustomAttribute($this->customAttributesManagement->getCustomAttributes($childProduct));
            $configurations[] = $configProduct;
        }
        return $configurations;
    }
}