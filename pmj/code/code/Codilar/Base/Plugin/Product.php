<?php

namespace Codilar\Base\Plugin;

use Codilar\Base\Helper\Data;
use Magento\Catalog\Block\Product\ListProduct;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Catalog\Model\ProductRepository;
use Magento\CatalogInventory\Api\StockRegistryInterface;
use Magento\ConfigurableProduct\Helper\Data as ConfigurableProductHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Directory\Model\PriceCurrency;
use Magento\Framework\Registry;
use Magento\Wishlist\Helper\Data as WishlistHelper;
use Magento\Wishlist\Model\WishlistFactory;
/**
 * Class Product
 * @package Codilar\Base\Plugin
 */
class Product
{
    /**
     * @var Configurable
     */
    protected $_configurableProductInstance;
    /**
     * @var ProductRepository
     */
    protected $_productRepository;
    /**
     * @var ConfigurableProductHelper
     */
    protected $_configurableProductHelper;
    /**
     * @var Registry
     */
    protected $_registry;
    /**
     * @var Data
     */
    protected $_helper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var ListProduct
     */
    protected $_listProduct;
    /**
     * @var WishlistHelper
     */
    protected $_wishlistHelper;
    /**
     * @var Compare
     */
    protected $_compareHelper;
    /**
     * @var ProductHelper
     */
    protected $_productHelper;
    /**
     * @var StockRegistryInterface
     */
    private $stockRegistry;
    /**
     * @var PriceCurrency
     */
    private $priceCurrency;
    /**
     * @var WishlistFactory
     */
    private $wishlistFactory;
    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * Product constructor.
     * @param Configurable                               $configurable
     * @param ProductRepository                          $productRepository
     * @param ConfigurableProductHelper                  $configurableProductHelper
     * @param Registry                                   $registry
     * @param Data                                       $helper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param ListProduct $listProduct
     * @param WishlistHelper $wishlistHelper
     * @param Compare $compareHelper
     * @param ProductHelper $productHelper
     * @param PriceCurrency $priceCurrency
     * @param StockRegistryInterface $stockRegistry
     * @param WishlistFactory $wishlistFactory
     * @param CustomerSession $customerSession
     */
    public function __construct(
        Configurable $configurable,
        ProductRepository $productRepository,
        ConfigurableProductHelper $configurableProductHelper,
        Registry $registry,
        Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        ListProduct $listProduct,
        WishlistHelper $wishlistHelper,
        Compare $compareHelper,
        ProductHelper $productHelper,
        PriceCurrency $priceCurrency,
        StockRegistryInterface $stockRegistry,
        WishlistFactory $wishlistFactory,
        CustomerSession $customerSession
    )
    {
        $this->_configurableProductInstance = $configurable;
        $this->_productRepository = $productRepository;
        $this->_configurableProductHelper = $configurableProductHelper;
        $this->_registry = $registry;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->_listProduct = $listProduct;
        $this->_wishlistHelper = $wishlistHelper;
        $this->_compareHelper = $compareHelper;
        $this->_productHelper = $productHelper;
        $this->stockRegistry = $stockRegistry;
        $this->priceCurrency = $priceCurrency;
        $this->wishlistFactory = $wishlistFactory;
        $this->customerSession = $customerSession;
    }

    /**
     * @param ProductHelper $subject
     * @param array         ...$params
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function beforeinitProduct(\Magento\Catalog\Helper\Product $subject, ...$params)
    {
        try{
            $product = $this->_productRepository->getById($params[0], false, $this->getStoreId());
            $parent = null;
            $child = null;
            if ($product->getTypeId() == "configurable") { // is a configurable product
                $this->_registry->register('codilar_configurable_product_type', "configurable");
                $parent = $product;
                $simpleProducts = $this->_configurableProductInstance->getUsedProductCollection($product);
                if(count($simpleProducts)){
                    $lowestPrices = [];
                    foreach ($simpleProducts as $simpleProduct){
                        $simpleProductRep = $this->_productRepository->getById($simpleProduct->getId(), false, $this->getStoreId());
                        $lowestPrices[$simpleProductRep->getId()] = $simpleProductRep->getFinalPrice();
                        $child = $simpleProductRep;
                    }
                    asort($lowestPrices);
                    foreach ($lowestPrices as $productId => $value){
                        $child = $this->_productRepository->getById($productId,false,$this->getStoreId());
                        break;
                    }
                    //$child = $this->_productRepository->getById($child);
                    $defaults = $this->getDefaultOptions($parent, $child);
                    $productData = $this->getChildrenProductData($parent, $simpleProducts);
                    $this->_registry->register('codilar_configurable_products_default', $defaults);
                    $this->_registry->register('codilar_configurable_products_data', $productData);
                }
            }
            return $params;
        }
        catch (\Exception $e){
            return $params;
        }
    }

    /**
     * Retrieve default options of child to be pre selected in parent
     * @param $parent
     * @param $child
     * @return array
     */
    protected function getDefaultOptions($parent, $child)
    {
        $attributes = $parent->getTypeInstance(true)->getConfigurableAttributes($parent);
        $response = [];
        foreach ($attributes as $attribute) {
            $attributeId = $attribute->getData('product_attribute')->getId();
            $attributeCode = $attribute->getData('product_attribute')->getData('attribute_code');
            $response[$attributeId] = $child->getData($attributeCode);
        }
        return $response;
    }

    /**
     * @return int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getStoreId()
    {
        $storeId = $this->_storeManager->getStore()->getId();
        return $storeId;
    }

    /**
     * @param $parent
     * @param $simpleProducts
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function getChildrenProductData($parent, $simpleProducts)
    {
        $excludeAttr = [];
        $response = [];
        foreach ($simpleProducts as $simpleProduct){
            $productData = [];
            $data = [];
            $product = $this->_productRepository->get($simpleProduct->getSku(),false,$this->getStoreId());
            $attributes = $product->getAttributes();
            foreach ($attributes as $attribute) {
                if ($attribute->getIsVisibleOnFront() && !in_array($attribute->getAttributeCode(), $excludeAttr)) {
                    $value = $attribute->getFrontend()->getValue($product);

                    if ($value instanceof Phrase) {
                        $value = (string)$value;
                    } elseif ($attribute->getFrontendInput() == 'price' && is_string($value)) {
                        $value = $this->priceCurrency->convertAndFormat($value);
                    }

                    if (is_string($value) && strlen($value)) {
                        $data[$attribute->getAttributeCode()] = [
                            'label' => __($attribute->getStoreLabel()),
                            'value' => $value,
                            'code' => $attribute->getAttributeCode(),
                        ];
                    }
                }
            }
            $productStock = 0;
            $stockItem = $this->stockRegistry->getStockItem($product->getId());
            if($stockItem->getIsInStock()){
                $productStock = $stockItem->getQty();
            }
            $productData['stock'] = $productStock;
            $productData['status'] = $product->getStatus();
            $productData['name'] = $product->getName();
            $productData['price'] = $product->getPrice();
            $productData['sku'] = $product->getSku();
            $productData['short_description'] = $product->getShortDescription();
            $productData['description'] = $product->getDescription();
            if ($product->getAttributes()) {
                $productData['attributes'] = $data;
            }
            $response[$product->getId()] = $productData;
        }
        return $response;
    }
}