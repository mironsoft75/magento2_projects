<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Helper;

use Codilar\Api\Helper\Emulator;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\ProductFactory;
use Magento\Directory\Model\Currency;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Image\AdapterFactory as ImageAdapterFactory;
use Magento\Store\Model\StoreManagerInterface;

class Product extends AbstractHelper
{
    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;
    /**
     * @var Emulator
     */
    private $emulator;
    /**
     * @var ProductModel\ImageFactory
     */
    private $imageFactory;
    /**
     * @var ImageBuilder
     */
    private $imageBuilder;
    /**
     * @var Currency
     */
    private $currency;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    private $productResource;
    /**
     * @var \Magento\Framework\Filesystem
     */
    private $filesystem;
    /**
     * @var ImageAdapterFactory
     */
    private $imageAdapterFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Product constructor.
     * @param Context $context
     * @param EavSetupFactory $eavSetupFactory
     * @param Emulator $emulator
     * @param ImageFactory $imageFactory
     * @param ImageBuilder $imageBuilder
     * @param Currency $currency
     * @param ProductFactory $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Magento\Framework\Filesystem $filesystem
     * @param ImageAdapterFactory $imageAdapterFactory
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        EavSetupFactory $eavSetupFactory,
        Emulator $emulator,
        ImageFactory $imageFactory,
        ImageBuilder $imageBuilder,
        Currency $currency,
        ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Magento\Framework\Filesystem $filesystem,
        ImageAdapterFactory $imageAdapterFactory,
        StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context);
        $this->eavSetupFactory = $eavSetupFactory;
        $this->emulator = $emulator;
        $this->imageFactory = $imageFactory;
        $this->imageBuilder = $imageBuilder;
        $this->currency = $currency;
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        $this->filesystem = $filesystem;
        $this->imageAdapterFactory = $imageAdapterFactory;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param $attributeCode
     * @param $attributeLabel
     * @param $attributeType
     * @param $attributeInputType
     * @param array $attributeSets
     * @param bool $required
     * @param bool $visible
     * @param int $defaultValue
     * @param bool $searchable
     * @param bool $filterable
     * @param bool $comparable
     * @param bool $visibleOnFront
     * @param bool $usedInProductListing
     * @param bool $unique
     * @param int $isUsedInGrid
     * @param int $isVisibleInGrid
     * @param string $source
     * @param string $backend
     * @param string $frontend
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function createProductAttribute(
        $attributeCode,
        $attributeLabel,
        $attributeType,
        $attributeInputType,
        $attributeSets = [],
        $required = true,
        $visible = true,
        $defaultValue = 1,
        $searchable = false,
        $filterable = false,
        $comparable = false,
        $visibleOnFront = true,
        $usedInProductListing = true,
        $unique = false,
        $isUsedInGrid = 1,
        $isVisibleInGrid = 1,
        $source = "",
        $backend = "",
        $frontend = ""
    ) {
        /** @var EavSetup $eavSetup */
        $eavSetup = $this->eavSetupFactory->create();
        $productEntity = $eavSetup->getEntityType(ProductModel::ENTITY);
        $eavSetup->addAttribute(
            $productEntity,
            $attributeCode,
            [
                'type' => $attributeType,
                'backend' => $backend,
                'frontend' => $frontend,
                'label' => $attributeLabel,
                'input' => $attributeInputType,
                'class' => '',
                'source' => $source,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => $visible,
                'required' => $required,
                'user_defined' => true,
                'default' => $defaultValue,
                'searchable' => $searchable,
                'filterable' => $filterable,
                'comparable' => $comparable,
                'visible_on_front' => $visibleOnFront,
                'used_in_product_listing' => $usedInProductListing,
                'unique' => $unique,
                'apply_to' => '',
                'is_used_in_grid' => $isUsedInGrid,
                'is_visible_in_grid'=> $isVisibleInGrid
            ]
        );
        $attributeSetIds = $attributeSets;
        if (!$attributeSetIds) {
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($productEntity);
        }
        foreach ($attributeSetIds as $attributeSetId) {
            $groupId = $eavSetup->getAttributeGroupId($productEntity, $attributeSetId, "product-details");
            $eavSetup->addAttributeToGroup(
                $productEntity,
                $attributeSetId,
                $groupId,
                $attributeCode,
                null
            );
        }
    }

    /**
     * @param ProductModel $_product
     * @return int
     */
    public function getDiscount($_product)
    {
        /**
         * @var $_product \Magento\Catalog\Model\Product
         */
        $originalPrice = $_product->getPrice();
        $finalPrice = $_product->getPriceInfo()->getPrice('final_price')->getAmount()->getValue();

        $percentage = 0;
        if ($originalPrice > $finalPrice) {
            $percentage = intval(($originalPrice - $finalPrice) * 100 / $originalPrice);
        }
        if ($percentage) {
            return $percentage;
        } else {
            return 0;
        }
    }

    /**
     * @param ProductModel $_product
     * @return string
     */
    public function getDiscountLabel($_product)
    {
        $discount = $this->getDiscount($_product);
        return $discount ? $discount . "% Off" : "";
    }

    /**
     * @param ProductModel $_product
     * @return string
     */
    public function getDiscountHtml($_product)
    {
        $discount = $this->getDiscount($_product);
        return $discount ? "<div class='product-discount'>" . $discount . "% Off</div>" : "";
    }

    /**
     * @param ProductModel $_product
     * @return string
     */
    public function getThumbnailUrl($_product)
    {
        $this->emulator->startEmulation(Area::AREA_FRONTEND);
        /** @var \Magento\Catalog\Helper\Image $image */
        $image = $this->imageFactory->create()->init($_product, "product_thumbnail_image")
            ->setImageFile($_product->getFile());
        $imageUrl = $image->getUrl();
        $this->emulator->stopEmulation();
        return (string)$imageUrl;
    }

    /**
     * @param ProductModel $_product
     * @param string $imageId
     * @return string
     */
    public function getImageUrl($_product, $imageId = "category_page_list")
    {
        $this->emulator->startEmulation(Area::AREA_FRONTEND);
        /** @var \Magento\Catalog\Helper\Image $image */
        $image = $this->imageFactory->create()->init($_product, $imageId)
            ->setImageFile($_product->getFile());
        $imageUrl = $image->getUrl();
        $this->emulator->stopEmulation();
        return (string)$imageUrl;
    }

    /**
     * @param $image
     * @param int $width
     * @param int $height
     * @return string
     */
    public function getProductImageResizeUrl($image, $width = 567, $height = 851)
    {
        try {
            $image = trim($image, '/');
            $absolutePath = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('catalog/product/') . $image;
            if (!file_exists($absolutePath)) {
                throw  new LocalizedException(__('Image not found'));
            }
            if ($width && $height) {
                $imageResized = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA)->getAbsolutePath('catalog/product/resized/' . $width . 'x' . $height . '/') . $image;
                if (!file_exists($imageResized)) { // Only resize image if not already exists.
                    //create image factory...
                    $imageResize = $this->imageAdapterFactory->create();
                    $imageResize->open($absolutePath);
                    $imageResize->constrainOnly(true);
                    $imageResize->keepTransparency(true);
                    $imageResize->keepFrame(false);
                    $imageResize->keepAspectRatio(true);
                    $imageResize->resize($width, $height);
                    //destination folder
                    $destination = $imageResized;
                    //save image
                    $imageResize->save($destination);
                }
                $resizedURL = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/resized/' . $width . 'x' . $height . '/' . $image;
            } else {
                $resizedURL = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/' . $image;
            }
        } catch (\Exception $exception) {
            try {
                $resizedURL = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product/placeholder/' . $this->scopeConfig->getValue('catalog/placeholder/image_placeholder');
            } catch (NoSuchEntityException $e) {
                $resizedURL = $image;
            }
        }
        return $resizedURL;
    }

    /**
     * @param $product
     * @param string $imageId
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getProductImage($product, $imageId = "product_page_image_large")
    {
        return $this->imageBuilder->setProduct($product)->setImageId($imageId)->create();
    }

    /**
     * @return string
     */
    public function getStoreCurrency()
    {
        return $this->currency->getCurrencySymbol();
    }
    /**
     * @param $html
     * @return string
     */
    public function validateAndFixHtmlTag($html)
    {
        $tidy = new \tidy();
        return $tidy->repairString($html);
    }

    /**
     * @param int $productId
     * @return ProductModel
     */
    public function getProduct($productId)
    {
        $product = $this->productFactory->create();
        $this->productResource->load($product, $productId);
        return $product;
    }
}
