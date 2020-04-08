<?php
/**
 * @package     mage2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Helper;

use Codilar\Core\Helper\Data as CoreHelper;
use Codilar\Core\Helper\Product as CoreProductHelper;
use Codilar\Product\Api\Data\Product\MediaGalleryInterface;
use Codilar\Product\Api\Data\Product\MediaGalleryInterfaceFactory;
use Codilar\Product\Model\Cache\ProductAttributes as ProductAttributesCache;
use Magento\Catalog\Model\Product\Option as ProductCustomOptions;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data;
use Magento\Framework\Pricing\PriceCurrencyInterface;

class ProductHelper
{
    /**
     * @var Data
     */
    private $priceHelper;
    /**
     * @var \Magento\Eav\Model\Entity\AttributeFactory
     */
    private $attributeFactory;
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute
     */
    private $attributeResource;
    /**
     * @var Configurable
     */
    private $configurable;
    /**
     * @var PriceCurrencyInterface
     */
    private $priceCurrency;
    /**
     * @var ProductAttributesCache
     */
    private $productAttributesCache;
    /**
     * @var ProductCustomOptions
     */
    private $productCustomOptions;
    /**
     * @var CoreHelper
     */
    private $coreHelper;
    /**
     * @var MediaGalleryInterface
     */
    private $mediaGallery;
    /**
     * @var MediaGalleryInterfaceFactory
     */
    private $mediaGalleryInterfaceFactory;
    /**
     * @var CoreProductHelper
     */
    private $coreProductHelper;

    /**
     * ProductHelper constructor.
     * @param Data $priceHelper
     * @param AttributeFactory $attributeFactory
     * @param Attribute $attributeResource
     * @param Configurable $configurable
     * @param PriceCurrencyInterface $priceCurrency
     * @param ProductAttributesCache $productAttributesCache
     * @param ProductCustomOptions $productCustomOptions
     * @param MediaGalleryInterfaceFactory $mediaGalleryInterfaceFactory
     * @param CoreHelper $coreHelper
     * @param CoreProductHelper $coreProductHelper
     */
    public function __construct(
        Data $priceHelper,
        AttributeFactory $attributeFactory,
        Attribute $attributeResource,
        Configurable $configurable,
        PriceCurrencyInterface $priceCurrency,
        ProductAttributesCache $productAttributesCache,
        ProductCustomOptions $productCustomOptions,
        MediaGalleryInterfaceFactory $mediaGalleryInterfaceFactory,
        CoreHelper $coreHelper,
        CoreProductHelper $coreProductHelper
    ) {
        $this->priceHelper = $priceHelper;
        $this->attributeFactory = $attributeFactory;
        $this->attributeResource = $attributeResource;
        $this->configurable = $configurable;
        $this->priceCurrency = $priceCurrency;
        $this->productAttributesCache = $productAttributesCache;
        $this->productCustomOptions = $productCustomOptions;
        $this->coreHelper = $coreHelper;
        $this->mediaGalleryInterfaceFactory = $mediaGalleryInterfaceFactory;
        $this->coreProductHelper = $coreProductHelper;
    }

    /**
     * @param float $originalPrice
     * @param float $finalPrice
     * @return float|int
     */
    public function getOfferPercentage($originalPrice, $finalPrice)
    {
        $percentage = 0.0;
        if ($originalPrice > $finalPrice && isset($finalPrice)) {
            $percentage = ($originalPrice - $finalPrice) * 100 / $originalPrice;
        }

        return number_format($percentage, 2);
    }

    /**
     * @param $product
     * @return \Codilar\Product\Api\Data\Product\MediaGalleryInterface[]
     */
    public function getProductImagesArray($product)
    {
        /**
         * @var \Magento\Catalog\Model\Product $product
         */
        $images = $product->getMediaGalleryImages();
        $imageArray = [];
        foreach ($images as $image) {
            /** @var \Codilar\Product\Api\Data\Product\MediaGalleryInterface $mediaGallery */
            $mediaGallery = $this->mediaGalleryInterfaceFactory->create();

            $mediaGallery->setBaseImage($this->coreProductHelper->getProductImageResizeUrl($image->getFile(), 561, 851))
                ->setThumbnailImage($this->coreProductHelper->getProductImageResizeUrl($image->getFile(), 50, 70))
                ->setZoomImage($this->coreProductHelper->getProductImageResizeUrl($image->getFile(), null, null));

            $imageArray[] = $mediaGallery;
        }
        return $imageArray;
    }

    /**
     * @param int $attributeId
     * @return \Magento\Eav\Model\Entity\Attribute
     * @throws NoSuchEntityException
     */
    public function getAttribute($attributeId)
    {
        $attribute = $this->attributeFactory->create();
        $this->attributeResource->load($attribute, $attributeId);
        if (!$attribute->getId()) {
            throw NoSuchEntityException::singleField("id", $attributeId);
        }
        return $attribute;
    }

    /**
     * @param $price
     * @return float
     */
    public function formatPrice($price)
    {
        return $this->priceCurrency->round($price);
    }

    /**
     * @param string $attributeCode
     * @return \Magento\Eav\Model\Entity\Attribute
     * @throws NoSuchEntityException
     */
    public function getAttributeByCode($attributeCode)
    {
        $attribute = $this->attributeFactory->create();
        try {
            $attribute->loadByCode(\Magento\Catalog\Model\Product::ENTITY, $attributeCode);
            if (!$attribute->getId()) {
                throw NoSuchEntityException::singleField("code", $attributeCode);
            }
        } catch (LocalizedException $e) {
            throw NoSuchEntityException::singleField("code", $attributeCode);
        }
        return $attribute;
    }

    /**
     * @param string[] $attributeCodes
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return array
     * @throws NoSuchEntityException
     */
    public function getAttributesData($attributeCodes, $product)
    {
        sort($attributeCodes);
        $cacheIdentifier = sprintf("%s_%s_%s", ProductAttributesCache::TYPE_IDENTIFIER, $product->getId(), md5(implode('', $attributeCodes)));
        $cacheData = $this->productAttributesCache->load($cacheIdentifier);
        if (!$cacheData) {
            $response = [];
            foreach ($attributeCodes as $attributeCode) {
                $attribute = $this->getAttributeByCode($attributeCode);
                $value = (string)$product->getData($attributeCode);
                if ($attribute->usesSource()) {
                    try {
                        $value = (string)$attribute->getSource()->getOptionText($value);
                    } catch (LocalizedException $e) {
                        throw NoSuchEntityException::singleField('source', $value);
                    }
                }
                if ($value) {
                    $response[] = [
                        'code' => $attributeCode,
                        'label' => $attribute->getStoreLabel(),
                        'value' => $value
                    ];
                }
            }
            $cacheData = $response;
            $this->productAttributesCache->save(\json_encode($response), $cacheIdentifier);
        } else {
            $cacheData = \json_decode($cacheData, true);
        }
        return $cacheData;
    }

    /**
     * @param \Magento\Catalog\Model\Product|\Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    public function getStockStatus($product)
    {
        return (bool)$product->isSalable();
    }

    /**
     * @param $attributeCode
     * @param $product
     * @throws NoSuchEntityException
     */
    public function getProductAttributeValue($attributeCode, $product)
    {
        $attribute = $this->getAttributeByCode($attributeCode);
        $value = $product->getData($attributeCode);
        if ($attribute->usesSource()) {
            try {
                $value = $attribute->getSource()->getOptionText($value);
            } catch (LocalizedException $e) {
                throw NoSuchEntityException::singleField('source', $value);
            }
        }
        return $value;
    }

    public function isProductCustomOptionsRequired($product)
    {
        $customOptions = $this->productCustomOptions->getProductOptionCollection($product);
        foreach ($customOptions as $customOption) {
            if ($customOption->getIsRequire()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $urlKey
     * @return int
     * @throws NoSuchEntityException
     */
    public function getProductIdByUrlKey($urlKey)
    {
        $categoryUrlKeys = explode('/', $urlKey);
        $productUrlKey = array_pop($categoryUrlKeys);
        $parentId = null;
        foreach ($categoryUrlKeys as $urlKey) {
            $parentId = $this->coreHelper->getEntityIdByUrlKey(\Magento\Catalog\Model\Category::ENTITY, $urlKey, $parentId);
        }
        return $this->coreHelper->getEntityIdByUrlKey(\Magento\Catalog\Model\Product::ENTITY, $productUrlKey, $parentId);
    }
}
