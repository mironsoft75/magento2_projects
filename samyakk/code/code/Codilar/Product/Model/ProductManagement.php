<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model;


use Codilar\Product\Api\Product\ConfigurationManagementInterface;
use Codilar\Product\Api\Data\ProductInterfaceFactory;
use Codilar\Product\Api\Product\CustomAttributesManagementInterface;
use Codilar\Product\Api\Product\CustomOptionsManagementInterface;
use Codilar\Product\Api\Product\MetaManagementInterface;
use Codilar\Product\Api\Product\OptionsValuesManagementInterface;
use Codilar\Product\Api\Product\SpecificationManagementInterface;
use Codilar\Product\Api\ProductManagementInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\Product\Helper\ProductHelper;
use Magento\Catalog\Model\Product\Option as ProductCustomOptions;

class ProductManagement implements ProductManagementInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductInterfaceFactory
     */
    private $productInterfaceFactory;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var MetaManagementInterface
     */
    private $metaManagement;
    /**
     * @var ConfigurationManagementInterface
     */
    private $configurationManagement;
    /**
     * @var OptionsValuesManagementInterface
     */
    private $optionsValuesManagement;
    /**
     * @var CustomOptionsManagementInterface
     */
    private $customOptionsManagement;
    /**
     * @var SpecificationManagementInterface
     */
    private $specificationManagement;
    /**
     * @var CustomAttributesManagementInterface
     */
    private $customAttributesManagement;
    /**
     * @var ProductCustomOptions
     */
    private $productCustomOption;

    /**
     * ProductManagement constructor.
     * @param ProductRepositoryInterface $productRepository
     * @param ProductInterfaceFactory $productInterfaceFactory
     * @param ProductHelper $productHelper
     * @param MetaManagementInterface $metaManagement
     * @param ConfigurationManagementInterface $configurationManagement
     * @param OptionsValuesManagementInterface $optionsValuesManagement
     * @param CustomOptionsManagementInterface $customOptionsManagement
     * @param SpecificationManagementInterface $specificationManagement
     * @param CustomAttributesManagementInterface $customAttributesManagement
     * @param ProductCustomOptions $productCustomOption
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductInterfaceFactory $productInterfaceFactory,
        ProductHelper $productHelper,
        MetaManagementInterface $metaManagement,
        ConfigurationManagementInterface $configurationManagement,
        OptionsValuesManagementInterface $optionsValuesManagement,
        CustomOptionsManagementInterface $customOptionsManagement,
        SpecificationManagementInterface $specificationManagement,
        CustomAttributesManagementInterface $customAttributesManagement,
        ProductCustomOptions $productCustomOption
    )
    {
        $this->productRepository = $productRepository;
        $this->productInterfaceFactory = $productInterfaceFactory;
        $this->productHelper = $productHelper;
        $this->metaManagement = $metaManagement;
        $this->configurationManagement = $configurationManagement;
        $this->optionsValuesManagement = $optionsValuesManagement;
        $this->customOptionsManagement = $customOptionsManagement;
        $this->specificationManagement = $specificationManagement;
        $this->customAttributesManagement = $customAttributesManagement;
        $this->productCustomOption = $productCustomOption;
    }

    /**
     * @param string $urlKey
     * @return \Codilar\Product\Api\Data\ProductInterface
     * @throws NoSuchEntityException
     */
    public function getProductByUrlKey($urlKey)
    {
        /** @var \Codilar\Product\Api\Data\ProductInterface $response */
        $response = $this->productInterfaceFactory->create();

        /** @var \Magento\Catalog\Api\Data\ProductInterface $product */
        $product = $this->productRepository->getById(
            $this->productHelper->getProductIdByUrlKey($urlKey)
        );

        if ($product->getStatus() != 1) {
            throw new NoSuchEntityException(__("The requested product does not exist"));
        }

        $response->setId($product->getId())
            ->setSku($product->getSku())
            ->setName($product->getName())
            ->setType($product->getTypeId())
            ->setStockStatus($this->productHelper->getStockStatus($product))
            ->setShortDescription($product->getShortDescription() ? $product->getShortDescription() : "")
            ->setLongDescription($product->getDescription() ? $product->getDescription() : "")
            ->setPrice($this->productHelper->formatPrice($product->getPrice()))
            ->setSpecialPrice($this->productHelper->formatPrice($product->getFinalPrice()))
            ->setOfferPercent($this->productHelper->getOfferPercentage($product->getPrice(), $product->getFinalPrice()))
            ->setMeta($this->metaManagement->getMetaData($product))
            ->setMediaGallery($this->productHelper->getProductImagesArray($product))
            ->setConfigurations($this->configurationManagement->getProductConfiguration($product))
            ->setOptionValues($this->optionsValuesManagement->getOptionsValue($product))
            ->setCustomOptions($this->customOptionsManagement->getProductCustomOptions($product))
            ->setSpecifications($this->specificationManagement->getProductSpecification($product))
            ->setCustomAttribute($this->customAttributesManagement->getCustomAttributes($product));
        return $response;

    }

    protected function hasCustomOptions($product) {
        $options = $this->productCustomOption->getProductOptionCollection($product);
        if (count($options) > 0) {
            return true;
        } else {
            return false;
        }
    }
}