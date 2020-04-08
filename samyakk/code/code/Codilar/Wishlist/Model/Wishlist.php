<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Wishlist\Model;

use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface;
use Codilar\Core\Helper\Product;
use Codilar\Product\Helper\ProductHelper;
use Codilar\Wishlist\Api\Data\WishlistInterface;
use Codilar\Wishlist\Api\Data\WishlistItemsInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Wishlist\Model\ItemFactory;
use Magento\Wishlist\Model\ResourceModel\Item\CollectionFactory;
use Magento\Wishlist\Model\ResourceModel\Wishlist as ResourceWishlist;
use Magento\Wishlist\Model\ResourceModel\Wishlist\Collection;
use Magento\Wishlist\Model\Wishlist as Subject;

class Wishlist extends Subject implements WishlistInterface
{
    /**
     * @var Product
     */
    private $productHelper;
    /**
     * @var ProductHelper
     */
    private $customProductHeper;

    /**
     * Wishlist constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Catalog\Helper\Product $catalogProduct
     * @param \Magento\Wishlist\Helper\Data $wishlistData
     * @param ResourceWishlist $resource
     * @param Collection $resourceCollection
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param ItemFactory $wishlistItemFactory
     * @param CollectionFactory $wishlistCollectionFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Framework\Math\Random $mathRandom
     * @param \Magento\Framework\Stdlib\DateTime $dateTime
     * @param ProductRepositoryInterface $productRepository
     * @param Product $productHelper
     * @param ProductHelper $customProductHeper
     * @param bool $useCurrentWebsite
     * @param array $data
     * @param Json|null $serializer
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Catalog\Helper\Product $catalogProduct,
        \Magento\Wishlist\Helper\Data $wishlistData,
        ResourceWishlist $resource, Collection $resourceCollection,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        ItemFactory $wishlistItemFactory,
        CollectionFactory $wishlistCollectionFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Framework\Math\Random $mathRandom,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        ProductRepositoryInterface $productRepository,
        Product $productHelper,
        ProductHelper $customProductHeper,
        bool $useCurrentWebsite = true,
        array $data = [],
        ?Json $serializer = null
    )
    {
        parent::__construct($context, $registry, $catalogProduct, $wishlistData, $resource, $resourceCollection, $storeManager, $date, $wishlistItemFactory, $wishlistCollectionFactory, $productFactory, $mathRandom, $dateTime, $productRepository, $useCurrentWebsite, $data, $serializer);
        $this->_storeManager->setCurrentStore($this->_storeManager->getDefaultStoreView()->getId());
        $this->productHelper = $productHelper;
        $this->customProductHeper = $customProductHeper;
    }

    /**
     * @return \Codilar\Wishlist\Api\Data\WishlistItemsInterface[]
     */
    public function getItems()
    {
        $collection = $this->getItemCollection();
        /** @var WishlistItemsInterface[] $items */
        $items = $collection->getItems();
        foreach ($items as $item) {
            $product = $this->productHelper->getProduct($item->getProductId());
            $item->setIsInStock($this->customProductHeper->getStockStatus($product));
            $product->setImage($this->productHelper->getImageUrl($product, 'product_base_image'));
            $product->setImageUrl($this->productHelper->getImageUrl($product, 'wishlist_product_image'));
            $product->setSpecialPrice($product->getSpecialPrice());
            $product->setShipTime($this->customProductHeper->getProductAttributeValue('ship_time', $product));
            $product->setOfferPercentage($this->customProductHeper->getOfferPercentage($product->getPrice(), $product->getSpecialPrice()));
            if ($product->getTypeId() == 'configurable') {
                $product->setIsAddable(false);
            } else if ($this->customProductHeper->isProductCustomOptionsRequired($product)){
                $product->setIsAddable(false);
            } else {
                $product->setIsAddable(true);
            }
            $item->setProduct($product);
        }
        return $items;
    }

    public function getItemCollection()
    {
        if ($this->_itemCollection === null) {
            $this->_itemCollection = $this->_wishlistCollectionFactory->create()->addWishlistFilter(
                $this
            )->addStoreFilter(
                $this->getSharedStoreIds()
            )->setVisibilityFilter(false);
        }

        return $this->_itemCollection;
    }

    /**
     * @return bool
     */
    public function getHasItems()
    {
        $this->setData("has_items", $this->getItemsCount() > 0);
        return $this->getData("has_items");
    }

    /**
     * @return bool
     */
    public function getStatus()
    {
        return $this->getData("status");
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->getData("message");
    }

    /**
     * @return bool|int|void
     */
    public function getShared()
    {
        parent::getShared();
    }

    /**
     * @return string|void
     */
    public function getSharingCode()
    {
        parent::getSharingCode();
    }

    /**
     * @param boolean $status
     * @return $this
     */
    public function setStatus($status)
    {
        return $this->setData('status', $status);
    }

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message)
    {
        return $this->setData('message', $message);
    }
}
