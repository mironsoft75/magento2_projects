<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24/7/19
 * Time: 2:00 PM
 */

namespace Codilar\CustomProductPage\Block\Product;

use Magento\Framework\View\Element\Template;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ProductRepository;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Codilar\Videostore\Helper\Data as VideoHelper;
use Magento\Framework\Registry;


/**
 * Class Details
 *
 * @package Codilar\CustomProductPage\Block\Product
 */
class Details extends Template
{
    const VIDEO_URL = "video_url";
    const LARGE_VIDEO_URL = "large_video_url";
    const MEDIUM_VIDEO_URL = "medium_video_url";
    const SMALL_VIDEO_URL = "small_video_url";
    const VIDEO_THUMBNAIL_URL = "video_thumbnail_url";
    /**
     * Logger Interface
     *
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * Registry
     *
     * @var Registry
     */
    protected $registry;
    /**
     * Product Repository
     *
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * Magento Price Data
     *
     * @var PriceHelper
     */
    protected $priceHelper;
    /**
     * Video Store Helper
     *
     * @var VideoHelper
     */
    protected $videoHelper;


    /**
     * Details constructor.
     * @param LoggerInterface $logger
     * @param Template\Context $context
     * @param ProductRepository $productRepository
     * @param PriceHelper $priceHelper
     * @param VideoHelper $videoHelper
     * @param Registry $registry
     * @param array $data
     */
    public function __construct
    (
        LoggerInterface $logger,
        Template\Context $context,
        ProductRepository $productRepository,
        PriceHelper $priceHelper,
        VideoHelper $videoHelper,
        Registry $registry,
        array $data = []
    )
    {
        $this->videoHelper = $videoHelper;
        $this->priceHelper = $priceHelper;
        $this->registry = $registry;
        $this->productRepository = $productRepository;
        $this->logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * Get Product
     *
     * @return mixed
     */
    public function getProduct()
    {
        try {
            return $this->registry->registry("current_product");
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }
    /**
     * Get ProductId
     *
     * @return mixed
     */
    public function getProductId()
    {
        try {
            return $this->getProduct()->getId();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }


    /**
     * Get Current Product Details
     *
     * @return array
     */
    public function getCurrentProductDetails()
    {

        try {
            $product = [];
            $imageArray = [];
            $id = $this->getProductId();
            $currentProduct = $this->productRepository->getById($id);
            $product["name"] = $currentProduct->getName();
            $product["thumbnail"] = $this->getImageUrl($currentProduct);
            $product["url"] = $currentProduct->getProductUrl();
            $product['id'] = $id;
            $product['price'] = $this->priceHelper
                ->currency($currentProduct->getFinalPrice(), true, false);
            if ($currentProduct->getCustomAttribute(self::VIDEO_URL)) {
                $product[self::VIDEO_URL] = $currentProduct
                    ->getCustomAttribute(self::VIDEO_URL)->getValue();
            } else {
                $product[self::VIDEO_URL] = null;
            }
            // if ($currentProduct->getCustomAttribute(self::LARGE_VIDEO_URL)) {
            //     $product[self::LARGE_VIDEO_URL] = $currentProduct
            //         ->getCustomAttribute(self::LARGE_VIDEO_URL)->getValue();
            // } else {
            //     $product[self::LARGE_VIDEO_URL] = null;
            // }
            // if ($currentProduct->getCustomAttribute(self::MEDIUM_VIDEO_URL)) {
            //     $product[self::MEDIUM_VIDEO_URL] = $currentProduct
            //         ->getCustomAttribute(self::MEDIUM_VIDEO_URL)->getValue();
            // } else {
            //     $product[self::MEDIUM_VIDEO_URL] = null;
            // }
            // if ($currentProduct->getCustomAttribute(self::SMALL_VIDEO_URL)) {
            //     $product[self::SMALL_VIDEO_URL] = $currentProduct
            //         ->getCustomAttribute(self::SMALL_VIDEO_URL)->getValue();
            // } else {
            //     $product[self::SMALL_VIDEO_URL] = null;
            // }
            if ($currentProduct->getCustomAttribute(self::VIDEO_THUMBNAIL_URL)) {
                $product[self::VIDEO_THUMBNAIL_URL] = $currentProduct
                    ->getCustomAttribute(self::VIDEO_THUMBNAIL_URL)->getValue();
            } else {
                $product[self::VIDEO_THUMBNAIL_URL] = null;
            }
            // if ($currentProduct->getMediaGalleryImages()) {
            //     $images = $currentProduct->getMediaGalleryImages();
            // }
            // if ($images) {
            //     foreach ($images as $image) {
            //         $imageData = $image->getUrl();
            //         array_push($imageArray, $imageData);
            //     }
            // }
            // $product['images'] = $imageArray;
            if ($currentProduct->getCustomAttribute('image_custom_urls')) {
                $product['image_custom_urls'] = $currentProduct->getCustomAttribute('image_custom_urls')->getValue();
            } else {
                $product['image_custom_urls']=null;
            }
            $stoneAndMetalBomVariantsDetails = $this->videoHelper
                ->getStoneAndMetalBomVariantsDetails($currentProduct);
            $product['metal_weight']
                = $stoneAndMetalBomVariantsDetails['metal_weight'];
            $product['stone_weight']
                = $stoneAndMetalBomVariantsDetails['stone_weight'];
            $product['stone_details'] = $this->videoHelper
                ->getStoneDetails(
                    $currentProduct,
                    $stoneAndMetalBomVariantsDetails['stone_variant_name']
                );
            $product['stone_details']['stone_pcs']
                = $stoneAndMetalBomVariantsDetails['stone_pcs'];
            $product['stock_status'] = $this->videoHelper
                ->getStockStatus($currentProduct->getSku());
            $product['variant_type'] = $currentProduct->getVariantType();
            $product['metal_details']=$this->videoHelper
                        ->getMetalDetails($currentProduct, $stoneAndMetalBomVariantsDetails['metal_variant_name']);
            // $product['metal_color']
            //     = $currentProduct->getAttributeText("metal_color");
            // $product['metal_type']=$currentProduct->getAttributeText("metal_type");
            // $product['metal_karat'] = $currentProduct->getAttributeText("karat");
            $product['location'] = $currentProduct
                ->getAttributeText("customer_friendly_location_name");
            $product['stock_code'] = $currentProduct->getStockCode();
            return $product;
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }
    }

    /**
     * @param $attributeValue
     * @return string
     */
    public function getAttributeValues($attributeValue)
    {
        try {
            if ($attributeValue) {
                if (is_array($attributeValue)) {
                    return implode(",", $attributeValue);
                } else {
                    return $attributeValue;
                }
            } else {
                return "-";
            }
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
        }

    }
}