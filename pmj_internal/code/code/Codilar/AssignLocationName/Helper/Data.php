<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/11/19
 * Time: 10:31 AM
 */

namespace Codilar\AssignLocationName\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\ResourceModel\Product\Action as ProductAction;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Codilar\ProductImport\Helper\AttributeHelper;
use Codilar\ProductImport\Helper\Data as PriceHelper;

/**
 * Class Data
 *
 * @package Codilar\AssignLocationName\Helper
 */
class Data extends AbstractHelper
{
    const YES = "Yes";

    /**
     * Logger
     *
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * ProductAction
     *
     * @var ProductAction
     */
    protected $productAction;
    /**
     * StoreManagerInterface
     *
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * Collection Factory
     *
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var Data
     */
    protected $priceHelper;
    /**
     * @var AttributeHelper
     */
    protected $attributeHelper;

    /**
     * Data constructor.
     * @param CollectionFactory $collectionFactory
     * @param ProductAction $productAction
     * @param StoreManagerInterface $storeManager
     * @param LoggerInterface $logger
     * @param PriceHelper $helper
     * @param AttributeHelper $attributeHelper
     * @param Context $context
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ProductAction $productAction,
        StoreManagerInterface $storeManager,
        LoggerInterface $logger,
        PriceHelper $helper,
        AttributeHelper $attributeHelper,
        Context $context
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->productAction = $productAction;
        $this->priceHelper = $helper;
        $this->attributeHelper = $attributeHelper;
        $this->_logger = $logger;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * GetCustomerFriendlyLocationName
     *
     * @param $locationName
     * @param $depts
     * @return string
     */
    public function getCustomerFriendlyLocationName($locationName, $depts)
    {
        try {
            if (isset($locationName) && isset($depts)) {
                $depts = explode(',', $depts);
                $customerFriendlyLocationName = $this->priceHelper
                    ->getCustomerFriendlyLocationName($locationName, $depts);
                return $this->attributeHelper
                    ->setAttributeValues(
                        "customer_friendly_location_name",
                        $customerFriendlyLocationName
                    );
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * SetProductVisibility
     *
     * @param $product
     * @param $customerFriendlyLocationNameValue
     * @return string
     */
    public function setProductVisibility($product, $customerFriendlyLocationNameValue)
    {
        try {
            $locationInstock = $this->priceHelper
                ->getLocationShowAsInStock(
                    $product->getLocationName(),
                    $product->getDept()
                );
            if (is_array($locationInstock)) {
                if (is_bool(array_search(self::YES, $locationInstock))) {
                    $locationDisplay = null;
                } else {
                    $locationDisplay = array_search(self::YES, $locationInstock);
                }
            }
            if (($product->getVideoThumbnailUrl() || $product->getVideoUrl() || $product->getImageCustomUrls())
                && isset($customerFriendlyLocationNameValue) && isset($locationDisplay)) {
                return "4";
            } else {
                return "3";
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * UpdateLocationName
     */
    public function updateLocationName()
    {
        try {

            /**
             * Product Collection
             *
             * @var \Magento\Catalog\Model\ResourceModel\Product\Collection $products
             */
            $products = $this->collectionFactory->create();
            $products->addAttributeToSelect('*');
            $stores = $this->storeManager->getStores(true);
            if ($products->getSize()) {
                foreach ($products as $productInfo) {
                    $customerFriendlyLocationNameValue = $this
                        ->getCustomerFriendlyLocationName($productInfo->getLocationName(), $productInfo->getDept());
                    $visibility = $this->setProductVisibility($productInfo, $customerFriendlyLocationNameValue);
                    foreach ($stores as $store) {
                        $this->storeManager->setCurrentStore($store->getId());
                        $updateAttributes = [
                            'customer_friendly_location_name' =>
                                $customerFriendlyLocationNameValue,
                            'visibility' => $visibility,
                        ];
                        $this->productAction->updateAttributes(
                            [$productInfo->getId()],
                            $updateAttributes,
                            $this->storeManager->getStore()->getId()
                        );
                    }
                }
            }

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}