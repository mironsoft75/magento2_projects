<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Model;

use Codilar\Catalog\Api\Data\ProductInterface;
use Codilar\Catalog\Model\Cache\Product as ProductCache;
use Codilar\Product\Helper\ProductHelper;
use Magento\Catalog\Model\ProductFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Codilar\Catalog\Api\Data\Product\ImageInterface;
use Codilar\Catalog\Api\Data\Product\ImageInterfaceFactory;

class Product extends DataObject implements ProductInterface
{
    /**
     * @var ProductFactory
     */
    private $productFactory;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    private $productResource;

    /**
     * @var \Codilar\Core\Helper\Product
     */
    private $productHelper;
    /**
     * @var Cache\Product
     */
    private $productCache;
    /**
     * @var ImageInterfaceFactory
     */
    private $imageInterfaceFactory;
    /**
     * @var array
     */
    private $data;
    /**
     * @var ProductHelper
     */
    private $catalogProductHelper;

    /**
     * Product constructor.
     * @param ProductFactory $productFactory
     * @param \Magento\Catalog\Model\ResourceModel\Product $productResource
     * @param \Codilar\Core\Helper\Product $productHelper
     * @param Cache\Product $productCache
     * @param ImageInterfaceFactory $imageInterfaceFactory
     * @param ProductHelper $catalogProductHelper
     * @param array $data
     */
    public function __construct(
        ProductFactory $productFactory,
        \Magento\Catalog\Model\ResourceModel\Product $productResource,
        \Codilar\Core\Helper\Product $productHelper,
        ProductCache $productCache,
        ImageInterfaceFactory $imageInterfaceFactory,
        ProductHelper $catalogProductHelper,
        array $data = []
    )
    {
        $this->productFactory = $productFactory;
        $this->productResource = $productResource;
        $this->productHelper = $productHelper;
        $this->productCache = $productCache;
        parent::__construct($data);
        $this->imageInterfaceFactory = $imageInterfaceFactory;
        $this->data = $data;
        $this->catalogProductHelper = $catalogProductHelper;
    }

    /**
     * @var null|\Magento\Catalog\Model\Product
     */
    protected $product = null;

    /**
     * @return \Magento\Catalog\Model\Product|null
     * @throws LocalizedException
     */
    protected function getProduct() {
        if (!$this->product instanceof \Magento\Catalog\Model\Product) {
            throw new LocalizedException(__("Product not initialized. Please use the %1 or %2 methods to initialize product", self::class."::load", self::class."::loadById"));
        }
        return $this->product;
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getId()
    {
        return $this->getProduct()->getId();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getName()
    {
        return $this->getProduct()->getName();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getSku()
    {
        return $this->getProduct()->getSku();
    }

    /**
     * @return float|null
     * @throws LocalizedException
     */
    public function getPrice()
    {
        return $this->getProduct()->getPrice();
    }

    /**
     * @return float
     * @throws LocalizedException
     */
    public function getFinalPrice()
    {
        return $this->getProduct()->getPriceInfo()->getPrice("final_price")->getValue();
    }

    /**
     * @return string|null
     * @throws LocalizedException
     */
    public function getTypeId()
    {
        return $this->getProduct()->getTypeId();
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getQty()
    {
        return $this->getProduct()->getQty();
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getUrlKey()
    {
        return $this->getProduct()->getUrlKey();
    }

    /**
     * @return string|null
     * @throws LocalizedException
     */
    public function getCreatedAt()
    {
        return $this->getProduct()->getCreatedAt();
    }


    /**
     * @return string
     * @throws LocalizedException
     */
    public function getUpdatedAt()
    {
        return $this->getProduct()->getUpdatedAt();
    }


    /**
     * @return float
     * @throws LocalizedException
     */
    public function getWeight()
    {
        return $this->getProduct()->getWeight();
    }

    /**
     * @return \Codilar\Catalog\Api\Data\Product\ImageInterface
     * @throws LocalizedException
     */
    public function getImageUrl()
    {
        /** @var ImageInterface $imageUrlData */
        $imageUrlData = $this->imageInterfaceFactory->create();
        $product = $this->getProduct();
        $imageUrlData->setThumbnailImage($this->productHelper->getImageUrl($product, 'offer_product_thumbnail'))
            ->setSmallImage($this->productHelper->getImageUrl($product, 'offer_product_small_image'))
            ->setBaseImage($this->productHelper->getImageUrl($product, 'offer_product_base_image'));
        return $imageUrlData;
    }

    /**
     * @param \Codilar\Catalog\Model\Product $product
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function load($product)
    {
        $this->product = $product;
        return $this;
    }

    /**
     * @param int $productId
     * @return \Magento\Catalog\Model\Product |\Codilar\Catalog\Api\Data\ProductInterface
     * @throws LocalizedException
     */
    public function loadById($productId)
    {
        $product = $this->productFactory->create();
        $this->productResource->load($product, $productId);
        $this->product = $product;
        $product->setData("slug", $product->getUrlKey());
        $product->setData("image_url", $this->getImageUrl());
        $product->setData('offer_percentage', $this->catalogProductHelper->getOfferPercentage($this->getProduct()->getPrice(), $this->getProduct()->getSpecialPrice()));
        return $product;
    }

    /**
     * @param int $id
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setId($id)
    {
        return $this->setData("id", $id);
    }

    /**
     * @param string $name
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setName($name)
    {
        return $this->setData("name", $name);
    }

    /**
     * @param string $sku
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setSku($sku)
    {
        return $this->setData("sku", $sku);
    }

    /**
     * @param float $price
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setPrice($price)
    {
        return $this->setData("price", $price);
    }

    /**
     * @param string $finalPrice
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setFinalPrice($finalPrice)
    {
        return $this->setData("final_price", $finalPrice);
    }

    /**
     * @param string $typeId
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setTypeId($typeId)
    {
        return $this->setData("type_id", $typeId);
    }

    /**
     * @param string $urlKey
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setUrlKey($urlKey)
    {
        return $this->setData("url_key", $urlKey);
    }

    /**
     * @param string $createdAt
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData("created_at", $createdAt);
    }

    /**
     * @param string $updatedAt
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData("updated_at", $updatedAt);
    }

    /**
     * @param float $weight
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setWeight($weight)
    {
        return $this->setData("weight", $weight);
    }

    /**
     * @param \Codilar\Catalog\Api\Data\Product\ImageInterface $imageUrl
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setImageUrl($imageUrl)
    {
        return $this->setData("image_url", $imageUrl);
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getImage()
    {
        return $this->getProduct()->getImage();
    }

    /**
     * @param string $image
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setImage($image)
    {
        return $this->setData("image", $image);
    }

    /**
     * @return int
     * @throws LocalizedException
     */
    public function getAttributeSetId()
    {
        return $this->getProduct()->getAttributeSetId();
    }

    /**
     * @param int $attributeSetId
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setAttributeSetId($attributeSetId)
    {
        return $this->setData("attribute_set_id", $attributeSetId);
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getSlug()
    {
        return $this->getUrlKey();
    }

    /**
     * @param string $slug
     * @return \Codilar\Catalog\Api\Data\ProductInterface
     */
    public function setSlug($slug)
    {
        return $this->setData("slug", $slug);
    }

    /**
     * @return string
     */
    public function getSpecialPrice()
    {
        return $this->getData('special_price');
    }

    /**
     * @param string $price
     * @return $this
     */
    public function setSpecialPrie($price)
    {
        return $this->setData('special_price', $price);
    }

    /**
     * @return string
     */
    public function getShipTime()
    {
        return $this->getData('ship_time');
    }

    /**
     * @param string $shipTime
     * @return $this
     */
    public function setShipTime($shipTime)
    {
        return $this->setData('ship_time', $shipTime);
    }

    /**
     * @return string
     */
    public function getOfferPercentage()
    {
        return $this->getData('offer_percentage');
    }

    /**
     * @param string $offerPercentage
     * @return $this
     */
    public function setOfferPercentage($offerPercentage)
    {
        return $this->setData('offer_percentage', $offerPercentage);
    }

    /**
     * @return boolean
     */
    public function getIsAddable()
    {
        return $this->getData('is_addable');
    }

    /**
     * @param boolean $isAddable
     * @return mixed
     */
    public function setIsAddable($isAddable)
    {
        return $this->setData('is_addable', $isAddable);
    }
}
