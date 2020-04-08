<?php

namespace Codilar\CategoryApi\Model\CategoryPage;

use Codilar\CategoryApi\Api\CategoryPage\Details\AdditionalAttributeManagementInterface;
use Codilar\CategoryApi\Api\CategoryPage\Details\ImagesManagementInterface;
use Codilar\CategoryApi\Api\CategoryPage\DetailsManagementInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterfaceFactory;
use Codilar\CategoryApi\Helper\CategoryHelper;
use Codilar\Core\Helper\Product as CoreProductHelper;
use Codilar\Product\Helper\ProductHelper;
use Magento\Catalog\Api\ProductRepositoryInterface;

class DetailsManagement implements DetailsManagementInterface
{
    /**
     * @var DetailsInterfaceFactory
     */
    private $detailsInterfaceFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var CoreProductHelper
     */
    private $coreProductHelper;
    /**
     * @var CategoryHelper
     */
    private $categoryHelper;
    /**
     * @var AdditionalAttributeManagementInterface
     */
    private $additionalAttributeManagement;
    /**
     * @var ImagesManagementInterface
     */
    private $imagesManagement;

    /**
     * DetailsManagement constructor.
     * @param DetailsInterfaceFactory $detailsInterfaceFactory
     * @param ProductRepositoryInterface $productRepository
     * @param ProductHelper $productHelper
     * @param CoreProductHelper $coreProductHelper
     * @param CategoryHelper $categoryHelper
     * @param AdditionalAttributeManagementInterface $additionalAttributeManagement
     * @param ImagesManagementInterface $imagesManagement
     */
    public function __construct(
        DetailsInterfaceFactory $detailsInterfaceFactory,
        ProductRepositoryInterface $productRepository,
        ProductHelper $productHelper,
        CoreProductHelper $coreProductHelper,
        CategoryHelper $categoryHelper,
        AdditionalAttributeManagementInterface $additionalAttributeManagement,
        ImagesManagementInterface $imagesManagement

    ) {
        $this->detailsInterfaceFactory = $detailsInterfaceFactory;
        $this->productRepository = $productRepository;
        $this->productHelper = $productHelper;
        $this->coreProductHelper = $coreProductHelper;
        $this->categoryHelper = $categoryHelper;
        $this->additionalAttributeManagement = $additionalAttributeManagement;
        $this->imagesManagement = $imagesManagement;
    }

    /**
     * @param array $products
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
     */
    public function getProductsDetails($products)
    {
        $details = [];
        foreach ($products as $product) {
            /** @var \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface $detailsInterface */
            $detailsInterface = $this->detailsInterfaceFactory->create();
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $this->productRepository->getById($product);
            $detailsInterface->setName($product->getName())
                ->setId($product->getId())
                ->setPrice($this->productHelper->formatPrice($product->getPrice()))
                ->setSku($product->getSku())
                ->setSpecialPrice($product->getSpecialPrice() ? $product->getSpecialPrice() : "")
                ->setDiscountPercentage($this->productHelper->getOfferPercentage($product->getPrice(), $product->getSpecialPrice()))
                ->setImages($this->imagesManagement->getImagesData($product))
                ->setInStock($product->isSalable() ? true : false)
                ->setProductTag($this->categoryHelper->getOptionsValueLabel($product->getData('product_tag')))
                ->setLink($this->categoryHelper->getPwaProductLink($product->getUrlKey()))
                ->setUrlKey($product->getUrlKey())
                ->setAdditionalAttributes($this->additionalAttributeManagement->getProductsAdditionalAttribiutesData($product));
            $details[] = $detailsInterface;
        }
        return $details;
    }
}
