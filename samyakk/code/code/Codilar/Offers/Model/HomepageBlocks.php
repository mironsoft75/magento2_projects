<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Model;

use Codilar\Offers\Api\Data\HomepageBlocksInterface;
use Codilar\Offers\Helper\Data;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Catalog\Model\Product\Attribute\Source\Status;

class HomepageBlocks extends AbstractModel implements HomepageBlocksInterface, IdentityInterface
{
    const CACHE_TAG = 'codilar_offers_homepageblocks';
    const IS_ACTIVE = "is_active";
    const ACTIVE_BLOCK = 1;
    const INACTIVE_BLOCK = 0;
    const HAS_PRODUCTS = "has_products";
    const HAS_PRODUCTS_TRUE = 1;
    const HAS_PRODUCTS_FALSE = 2;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var \Codilar\Catalog\Api\Data\ProductInterface
     */
    private $productInterface;

    /**
     * HomepageBlocks constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Data $helper
     * @param \Codilar\Catalog\Api\Data\ProductInterface $productInterface
     * @param AbstractResource|null $resource
     * @param AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Data $helper,
        \Codilar\Catalog\Api\Data\ProductInterface $productInterface,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->helper = $helper;
        $this->productInterface = $productInterface;
    }

    /**
     * Inititalise the resource model.
     */
    protected function _construct()
    {
        $this->_init('Codilar\Offers\Model\ResourceModel\HomepageBlocks');
    }

    /**
     * @return array|string[]
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return int
     */
    public function getBlockId()
    {
        return $this->getId();
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->getData("title");
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->setData("title", $title);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getShowTitle()
    {
        return (bool)$this->getData("show_title");
    }

    /**
     * @param boolean $showTitle
     * @return $this
     */
    public function setShowTitle($showTitle)
    {
        $this->setData("show_title", (bool)$showTitle);
        return $this;
    }

    /**
     * @return int
     */
    public function getHasProducts()
    {
        return $this->getData("has_products");
    }

    /**
     * @param int $hasProducts
     * @return $this
     */
    public function setHasProducts($hasProducts)
    {
        $this->setData("has_products", $hasProducts);
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockData()
    {
        return $this->getData("block_data");
    }

    /**
     * @param string $blockData
     * @return $this
     */
    public function setBlockData($blockData)
    {
        $this->setData("block_data", $blockData);
        return $this;
    }

    /**
     * @return string
     */
    public function getBlockStaticData()
    {
        return $this->getData("block_static_data");
    }

    /**
     * @param string $blockStaticData
     * @return $this
     */
    public function setBlockStaticData($blockStaticData)
    {
        $this->setData("block_static_data", $blockStaticData);
        return $this;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        return $this->getData("start_date");
    }

    /**
     * @param string $startDate
     * @return $this
     */
    public function setStartDate($startDate)
    {
        $this->setData("start_date", $startDate);
        return $this;
    }

    /**
     * @return string
     */
    public function getEndDate()
    {
        return $this->getData("end_date");
    }

    /**
     * @param string $endDate
     * @return $this
     */
    public function setEndDate($endDate)
    {
        $this->setData("end_date", $endDate);
        return $this;
    }

    /**
     * @return int
     */
    public function getSortOrder()
    {
        return $this->getData("sort_order");
    }

    /**
     * @param int $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder)
    {
        $this->setData("sort_order", $sortOrder);
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return (bool)$this->getData("is_active");
    }

    /**
     * @param boolean $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        $this->setData("is_active", (bool)$isActive);
        return $this;
    }

    /**
     * @return ProductInterface[]
     */
    public function getBlockProducts()
    {
        $blockData = $this->getBlockData();
        $items = [];
        if ($blockData) {
            $ids = explode(",", $blockData);
            $collection = $this->helper->getOfferProducts($ids);
            if ($collection->getSize()) {
                /** @var ProductInterface $item */
                foreach ($collection as $item) {
                    $items[] = $item;
                }
            }
        }
        return $items;
    }

    /**
     * @return \Codilar\Catalog\Api\Data\ProductInterface[]
     */
    public function getItems()
    {
        return $this->getProducts();
    }

    /**
     * @param \Codilar\Catalog\Api\Data\ProductInterface[] $items
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setItems($items)
    {
        $this->setData("items", $items);
        return $this;
    }

    /**
     * @return \Codilar\Catalog\Api\Data\ProductInterface[]
     */
    protected function getProducts()
    {
        $productIds = $this->getBlockData();
        $productIds = explode(",", $productIds);

        $productsData = [];
        if ($productIds) {
            foreach ($productIds as $key => $id) {
                if ($id != "") {
                    /** @var ProductInterface $product */
                    $product = $this->productInterface->loadById($id);
                    if ($product->getId() && $product->getStatus() == Status::STATUS_ENABLED) {
                        $productsData[] = $product;
                    }
                }
            }
        }
        return $productsData;
    }

    /**
     * @param int $blockId
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setBlockId($blockId)
    {
        return $this->setData("block_id", $blockId);
    }

    /**
     * @return int
     */
    public function getDisplayType()
    {
        return $this->getData("display_type");
    }

    /**
     * @param int $displayType
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface
     */
    public function setDisplayType($displayType)
    {
        return $this->setData("display_type");
    }

    /**
     * @return string
     */
    public function getDesignIdentifier()
    {
        return $this->getData('design_identifier');
    }

    /**
     * @param string $designIdentifier
     * @return $this
     */
    public function setDesignIdentifier($designIdentifier)
    {
        return $this->setData('design_identifier', $designIdentifier);
    }
}
