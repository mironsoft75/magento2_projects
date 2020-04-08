<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Block;

use Codilar\Offers\Helper\Data;
use Codilar\Offers\Model\HomepageBlocks as HomepageBlocksModel;
use Codilar\Offers\Model\ResourceModel\HomepageBlocks\CollectionFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Block\Product\AbstractProduct;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Cms\Model\Template\Filter;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class HomepageBlocks extends Template
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var AbstractProduct
     */
    private $abstractProduct;
    /**
     * @var ListProduct
     */
    private $listProduct;
    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Filter
     */
    private $cmsFilter;
    /**
     * @var \Codilar\Core\Helper\Data
     */
    private $coreHelper;
    /**
     * @var \Codilar\Core\Helper\Product
     */
    private $coreProductHelper;
    /**
     * @var FilterProvider
     */
    private $filterProvider;

    /**
     * HomepageBlocks constructor.
     *
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param ScopeConfigInterface $scopeConfig
     * @param ProductRepositoryInterface $productRepository
     * @param Data $helper
     * @param AbstractProduct $abstractProduct
     * @param ListProduct $listProduct
     * @param ProductCollectionFactory $productCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param Filter $cmsFilter
     * @param \Codilar\Core\Helper\Data $coreHelper
     * @param \Codilar\Core\Helper\Product $coreProductHelper
     * @param FilterProvider $filterProvider
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        ScopeConfigInterface $scopeConfig,
        ProductRepositoryInterface $productRepository,
        Data $helper,
        AbstractProduct $abstractProduct,
        ListProduct $listProduct,
        ProductCollectionFactory $productCollectionFactory,
        StoreManagerInterface $storeManager,
        Filter $cmsFilter,
        \Codilar\Core\Helper\Data $coreHelper,
        \Codilar\Core\Helper\Product $coreProductHelper,
        FilterProvider $filterProvider,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory    = $collectionFactory;
        $this->scopeConfig          = $scopeConfig;
        $this->productRepository    = $productRepository;
        $this->helper               = $helper;
        $this->abstractProduct      = $abstractProduct;
        $this->listProduct          = $listProduct;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->storeManager = $storeManager;
        $this->cmsFilter = $cmsFilter;
        $this->coreHelper = $coreHelper;
        $this->coreProductHelper = $coreProductHelper;
        $this->filterProvider = $filterProvider;
    }

    /**
     * Sets the template and returns it.
     *
     * @return Template
     */
    protected function _prepareLayout()
    {
        $this->setTemplate("Codilar_Offers::homepageBlocks.phtml");
        return parent::_prepareLayout();
    }

    /**
     * Get active blocks collection.
     *
     * @return bool|\Codilar\Offers\Model\ResourceModel\HomepageBlocks\Collection
     */
    public function getBlocks()
    {
        $currentDate = $this->coreHelper->getCurrentDate();
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(HomepageBlocksModel::IS_ACTIVE, HomepageBlocksModel::ACTIVE_BLOCK);
        $collection->addFieldToFilter("start_date", ["lteq" => $currentDate]);
        $collection->addFieldToFilter("end_date", ["gteq" => $currentDate]);
        $collection->setOrder("sort_order", SortOrder::SORT_ASC);
        if ($collection->getSize()) {
            return $collection;
        }
        return false;
    }

    /**
     * Get the store wise weight unit string.
     *
     * @return string
     */
    public function getWeightUnit()
    {
        return $this->scopeConfig->getValue("general/locale/weight_unit", ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get discount label for the specified product.
     *
     * @param $product
     * @return bool|string
     */
    public function getDiscountLabel($product)
    {
        return $this->helper->getDiscountLabel($product);
    }

    /**
     * Get Product details by Id.
     *
     * @param int $productId
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProductById($productId)
    {
        try {
            return $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * Get Product details by SKU.
     *
     * @param string $sku
     * @return bool|\Magento\Catalog\Api\Data\ProductInterface
     */
    public function getProductBySku($sku)
    {
        try {
            return $this->productRepository->get($sku);
        } catch (NoSuchEntityException $e) {
            return false;
        }
    }

    /**
     * Get product price html.
     *
     * @param Product $product
     * @return string
     * @throws NoSuchEntityException
     */
    public function getProductPriceHtml($product)
    {
        return $this->abstractProduct->getProductPrice($product);
    }

    /**
     * Get product details html.
     *
     * @param $product
     * @return mixed
     */
    public function getProductDetailsHtml($product)
    {
        return $this->abstractProduct->getProductDetailsHtml($product);
    }

    /**
     * Get the product image html.
     *
     * @param $product
     * @return \Magento\Catalog\Block\Product\Image
     */
    public function getProductImageHtml($product)
    {
        return $this->helper->getProductImageHtml($product, "category_page_list");
    }


    /**
     * @param $blockItem
     * @return array|bool
     * @throws NoSuchEntityException
     */
    public function getBlockProductDetails($blockItem)
    {
        $blockItem = (array)$blockItem->getData();
        if ($blockItem["has_products"]) {
            $productIds = explode(',',$blockItem["block_data"]);
            if ($productIds && is_array($productIds)) {
                $data = [];
                $productCollection = $this->getProductCollection($productIds);
                if ($productCollection->getSize()) {
                    /** @var Product $product */
                    foreach ($productCollection as $product) {
                        $data[$product->getId()] = [
                            "id"                => $product->getId(),
                            "sku"               => $product->getSku(),
                            "name"              => $product->getName(),
                            "price"             => $this->getProductPriceHtml($product),
                            "weight"            => $product->getWeight().$this->getWeightUnit(),
                            "image"             => $this->getProductImageHtml($product),
                            "discount_label"    => $this->getDiscountLabel($product),
                            "url"               => $product->getProductUrl(),
                            "is_saleable"       => $product->isSaleable(),
                            "is_available"      => $product->isAvailable()
                        ];
                    }
                    return $data;
                } else {
                    return false;
                }
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Get the add to cart post params.
     *
     * @param $product
     * @return array
     */
    public function getAddToCartPostParams($product)
    {
        return $this->listProduct->getAddToCartPostParams($product);
    }

    /**
     * Get product collection for specified ids.
     *
     * @param null $productIds
     * @return bool|\Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    public function getProductCollection($productIds = null)
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter("status", 1);
        $collection->addAttributeToSelect("*");
        if ($productIds) {
            $collection->addAttributeToFilter("entity_id", ["in" => $productIds]);
        }
        $collection->setOrder('name', SortOrder::SORT_ASC);
        if ($collection->getSize()) {
            return $collection;
        }
        return false;
    }

    /**
     * Filters the cms static content.
     *
     * @param $data
     * @return string
     */
    public function filterStaticData($data)
    {
        return $this->coreProductHelper->validateAndFixHtmlTag($this->filterProvider->getBlockFilter()->setStoreId($this->_storeManager->getStore()->getId())->filter($data));
    }

    /**
     * Get current date.
     *
     * @return string
     */
    public function getCurrentDate()
    {
        return $this->coreHelper->getCurrentDate();
    }
}