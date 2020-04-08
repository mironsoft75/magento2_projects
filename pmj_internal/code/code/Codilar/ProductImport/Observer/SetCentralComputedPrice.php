<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 24/5/19
 * Time: 4:38 PM
 */

namespace Codilar\ProductImport\Observer;

use Codilar\ProductImport\Helper\AttributeHelper;
use Codilar\ProductImport\Helper\Data;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\ObserverInterface;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

/**
 * Class SetCentralComputedPrice
 * @package Codilar\ProductImport\Observer
 */
class SetCentralComputedPrice implements ObserverInterface
{
    const YES = "Yes";
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Data
     */
    protected $_priceHelper;
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var AttributeHelper
     */
    protected $_attributeHelper;

    /**
     * SetCentralComputedPrice constructor.
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     * @param Data $helper
     * @param AttributeHelper $attributeHelper
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        RequestInterface $request,
        LoggerInterface $logger,
        Data $helper,
        AttributeHelper $attributeHelper,
        StoreManagerInterface $storeManager

    )
    {
        $this->_logger = $logger;
        $this->_request = $request;
        $this->_priceHelper = $helper;
        $this->_attributeHelper = $attributeHelper;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param $locationName
     * @param $depts
     * @return string
     */
    public function getCustomerFriendlyLocationName($locationName, $depts)
    {
        try {
            if (isset($locationName) && isset($depts)) {
                $depts = explode(',', $depts);
                $customerFriendlyLocationName = $this->_priceHelper
                    ->getCustomerFriendlyLocationName($locationName, $depts);
                return $this->_attributeHelper
                    ->setAttributeValues("customer_friendly_location_name", $customerFriendlyLocationName);
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $product
     * @param $attributeName
     * @param $attributeValueText
     */
    public function setProductMultiSelectValues($product, $attributeName, $attributeValueText)
    {
        try {
            $sourceModel = $product->getResource()
                ->getAttribute($attributeName)->getSource();
            $attributeValueIds = array_map(array($sourceModel, 'getOptionId'), $attributeValueText);
            $product->setData($attributeName, $attributeValueIds);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $product
     * @param $attributeName
     * @param $attributeValues
     */
    public function setProductMetalColor($product, $attributeName, $attributeValues)
    {
        try {
            $metalColors = [];
            foreach ($attributeValues as $metalColor) {
                $attributeValueText = explode('/', $metalColor);
                foreach ($attributeValueText as $attributeText) {
                    $attributeValue = $this->_attributeHelper->
                    setAttributeValues($attributeName, $attributeText);
                    $product->setMetalColor($attributeValue);
                    array_push($metalColors, $attributeText);
                }
            }
            $metalColors = array_unique($metalColors);
            $this->setProductMultiSelectValues($product, $attributeName, $metalColors);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());

        }
    }

    /**
     * @param $product
     * @param $attributeName
     * @param $attributeValues
     */
    public function setProductKarat($product, $attributeName, $attributeValues)
    {
        try {
            $metalKarats = [];
            foreach ($attributeValues as $metalKarat) {
                $attributeValueText = explode('/', $metalKarat);
                foreach ($attributeValueText as $attributeText) {
                    $attributeValue = $this->_attributeHelper->
                    setAttributeValues($attributeName, $attributeText);
                    $product->setKarat($attributeValue);
                    array_push($metalKarats, $attributeText);
                }
            }
            $metalKarats = array_unique($metalKarats);
            $this->setProductMultiSelectValues($product, $attributeName, $metalKarats);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());

        }
    }

    /**
     * @param $product
     * @param $attributeName
     * @param $attributeValues
     */
    public function setProductMetalType($product, $attributeName, $attributeValues)
    {
        try {
            $metalTypes = [];
            foreach ($attributeValues as $metaltype) {
                $attributeValueText = explode('/', $metaltype);
                foreach ($attributeValueText as $attributeText) {
                    $attributeValue = $this->_attributeHelper->
                    setAttributeValues($attributeName, $attributeText);
                    $product->setMetalType($attributeValue);
                    array_push($metalTypes, $attributeText);
                }
            }
            $metalTypes = array_unique($metalTypes);
            $this->setProductMultiSelectValues($product, $attributeName, $metalTypes);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());

        }
    }

    /**
     * @param $product
     * @param $attributeName
     * @param $attributeValues
     */
    public function setProductCustomType($product, $attributeName, $attributeValues)
    {
        try {
            $attributeValueText = explode('/', $attributeValues);
            foreach ($attributeValueText as $attributeText) {
                $attributeValue = $this->_attributeHelper->
                setAttributeValues($attributeName, $attributeText);
                $product->setCustomProductType($attributeValue);
            }
            $this->setProductMultiSelectValues($product, $attributeName, $attributeValueText);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());

        }
    }

    /**
     * @param $karatColor
     * @param $product
     */
    public function setProductMetalDetails($karatColor, $product)
    {

        try {
            $metalColorDetails = [];
            $metalKaratDetails = [];
            $metalTypeDetails = [];
            $karatColorDetails = explode('/', $karatColor);
            foreach ($karatColorDetails as $karatColorDetail) {
                $metalDetails = $this->_attributeHelper->getMetalDetails($karatColorDetail);
                $metalType = $metalDetails->getMetalType();
                if ($metalType == "Platinum") {
                    $karat = $metalDetails->getKarat();
                    $karatValue = $this->_attributeHelper->setAttributeValues("karat", $karat);
                    $product->setKarat($karatValue);
                    array_push($metalKaratDetails, $karatValue);
                } else {
                    $karat = $metalDetails->getKarat();
                    array_push($metalKaratDetails, $karat);

                }
                $metalColor = $metalDetails->getMetalColor();
                if ($metalColor) {
                    array_push($metalColorDetails, $metalColor);
                }
                if ($metalType) {
                    array_push($metalTypeDetails, $metalType);
                }
            }
            $metalTypeDetails = array_unique($metalTypeDetails);
            $metalColorDetails = array_unique($metalColorDetails);
            $metalKaratDetails = array_unique($metalKaratDetails);
            $this->setProductMetalType($product, "metal_type", $metalTypeDetails);
            $this->setProductMetalColor($product, "metal_color", $metalColorDetails);
            $this->setProductKarat($product, "karat", $metalKaratDetails);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * Set Product Visibility
     *
     * @param $product
     */
    public function setProductVisibility($product)
    {
        try {

            $locationInstock = $this->_priceHelper->getLocationShowAsInStock($product->getLocationName(),
                $product->getDept());
            if (is_array($locationInstock)) {
                if (is_bool(array_search(self::YES, $locationInstock))) {
                    $locationDisplay = null;
                } else {
                    $locationDisplay = array_search(self::YES, $locationInstock);
                }
            }
            if (($product->getVideoThumbnailUrl() || $product->getVideoUrl() || $product->getImageCustomUrls())
                && $product->getCustomerFriendlyLocationName() && isset($locationDisplay)) {
                $product->setVisibility("4");
            } else {
                $product->setVisibility("3");
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * Compute Product Price
     *
     * @param $product
     * @param $storeId
     */
    public function computeProductPrice($product, $storeId)
    {
        try {
            $gstPercentage = $this->_priceHelper->gstPercentage();
            $override = $product->getAttributeText('price_override');
            if ($override == self::YES) {
                $metalPrice = $this->_priceHelper->metalPriceCalculation($product, $storeId);
                $diamondPrice = $this->_priceHelper->stonePricecalculation($product, $storeId);
                $grandTotal = $metalPrice + $diamondPrice;
                if ($this->_priceHelper->computeGst() && isset($gstPercentage)) {
                    $gstAmount = ($grandTotal * $gstPercentage) / 100;
                    $total = $grandTotal + $gstAmount;
                    $product->setPrice($total);
                } else {
                    $product->setPrice($grandTotal);
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this|void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            $storeId = $this->_storeManager->getStore()->getId();
            $this->_storeManager->setCurrentStore($this->_storeManager->getStore()->getId());
            $product = $observer->getProduct();
            $product->setTypeId(
                \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE
            );
            if ($this->_priceHelper->computeProductPrice()) {
                $this->computeProductPrice($product, $storeId);
            }
            $variantName = $product->getVariantName();
            $variantType = explode('-', $variantName);
            $product->setVariantType($variantType[0]);
            if ($this->_priceHelper->assignCategories()) {
                $categoryIds = $this->_priceHelper->getCategoryIds($variantName);
                $product->setCategoryIds($categoryIds);
            }
            $customerFriendlyLocationNameValue = $this->getCustomerFriendlyLocationName($product->getLocationName(),
                $product->getDept());
            $product->setCustomerFriendlyLocationName($customerFriendlyLocationNameValue);
            $product->setMetalWt($product->getNetWeight());
            $product->setDiamondWt($this->_priceHelper->getDiamondWeight($product));
            $productType = $this->_attributeHelper->getProductType($variantType[0]);
            $this->setProductCustomType($product, "custom_product_type", $productType);
            $karatColor = $product->getKaratColor();
            $this->setProductMetalDetails($karatColor, $product);
            $this->setProductVisibility($product);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}
