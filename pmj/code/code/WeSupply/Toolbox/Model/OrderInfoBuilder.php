<?php

namespace WeSupply\Toolbox\Model;

/**
 * Class OrderInfoBuilder
 * @package WeSupply\Toolbox\Model
 */
class OrderInfoBuilder implements \WeSupply\Toolbox\Api\OrderInfoBuilderInterface
{

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    protected $orderRepositoryInterface;

    /**
     * @var \Magento\Framework\Event\ManagerInterface
     */
    protected $eventManager;

    /**
     * @var \Magento\Directory\Model\CountryFactory
     */
    protected $countryFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterfaceFactory
     */
    protected $productRepositoryInterfaceFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManagerInterface;

    /**
     * @var string
     * url to media directory
     */
    protected $mediaUrl;

    /**
     * @var string
     * order status label
     */
    protected $orderStatusLabel;

    /**
     * @var array
     */
    protected $weSupplyStatusMappedArray;

    private $timezone;


    /**
     * @string  product image subdirectory
     */
    CONST PRODUCT_IMAGE_SUBDIRECTORY = 'catalog/product';

    /**
     * @string used as prefix for wesupply order id to avoid duplicate id with other providers (aptos)
     */
    CONST PREFIX = 'mage_';

    /**
     * @int
     */
    CONST ITEM_STATUS_SHIPPED = 1;

    CONST EXCLUDED_ITEMS
        = [
            1 => \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE,
            2 => \Magento\Catalog\Model\Product\Type::TYPE_VIRTUAL
        ];


    /**
     * OrderInfoBuilder constructor.
     * @param \Magento\Sales\Api\OrderRepositoryInterface $orderRepositoryInterface
     * @param \Magento\Framework\Event\ManagerInterface $eventManager
     * @param \Magento\Directory\Model\CountryFactory $countryFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryInterfaceFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManagerInterface
     * @param \WeSupply\Toolbox\Helper\WeSupplyMappings $weSupplyMappings
     */
    public function __construct(
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepositoryInterface,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Directory\Model\CountryFactory $countryFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryInterfaceFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManagerInterface,
        \WeSupply\Toolbox\Helper\WeSupplyMappings $weSupplyMappings,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone
    )
    {
        $this->orderRepositoryInterface = $orderRepositoryInterface;
        $this->eventManager = $eventManager;
        $this->countryFactory = $countryFactory;
        $this->logger = $logger;
        $this->productRepositoryInterfaceFactory = $productRepositoryInterfaceFactory;
        $this->storeManagerInterface = $storeManagerInterface;
        $this->weSupplyStatusMappedArray = $weSupplyMappings->mapOrderStateToWeSupplyStatus();
        $this->timezone = $timezone;
     }

    /**
     * @param $flag
     */
    public function setDebug($flag)
    {
        $this->debug = $flag;
    }

    /**
     * @param $orderId
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function gatherInfo($orderId)
    {
        try {
            $order = $this->orderRepositoryInterface->get($orderId);
        } catch (\Exception $ex) {
            $this->logger->error("WeSupply Error: Order with id $orderId not found");
            return [];
        }
        $orderData = $order->getData();
        $this->orderStatusLabel = $order->getStatusLabel();
        if(!is_string($this->orderStatusLabel))
        {
            $this->orderStatusLabel = $order->getStatusLabel()->__toString();
        }

        $storeManager = $this->storeManagerInterface->getStore($orderData['store_id']);
        $this->mediaUrl = $storeManager->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);



        $orderData['wesupply_updated_at'] = date('Y-m-d H:i:s');

        unset($orderData['extension_attributes']);
        unset($orderData['items']);

        /** Gather order items information */
        $items = $order->getItems();
        foreach ($items as $item) {
            $itemData = $item->getData();
            if (isset($itemData['parent_item'])) {
                continue;
            }

            unset($itemData['has_children']);
            $orderData['OrderItems'][] = $itemData;
        }

        /** Set billing and shipping Address */
        $billingAddressData = $order->getBillingAddress()->getData();
        $orderData['billingAddressInfo'] = $billingAddressData;

        /** Downloadable product order have no shipping address */
        $shippingAdressData = $billingAddressData;
        if ($order->getShippingAddress()) {
            $shippingAdressData = $order->getShippingAddress()->getData();
        }
        $orderData['shippingAddressInfo'] = $shippingAdressData;

        /** Gather the shipments and trackings information */
        $shipmentTracks = [];
        $shipmentData = [];
        $shipmentCollection = $order->getShipmentsCollection();

        if ($shipmentCollection->getSize()) {
            foreach ($shipmentCollection->getItems() as $shipment) {
                $tracks = $shipment->getTracksCollection();
                /** @TODO If mutiple track numbers used in magento last one is used now */
                foreach ($tracks->getItems() as $track) {
                    $shipmentTracks[$track['parent_id']]['track_number'] = $track['track_number'];
                    $shipmentTracks[$track['parent_id']]['title'] = $track['title'];
                    $shipmentTracks[$track['parent_id']]['carrier_code'] = $track['carrier_code'];
                }

                $shipmentItems = $shipment->getItemsCollection();
                foreach ($shipmentItems->getItems() as $shipmentItem) {
                    /** Default empty values for non existing tracking */
                    if (!isset($shipmentTracks[$shipmentItem['parent_id']])) {
                        $shipmentTracks[$shipmentItem['parent_id']]['track_number'] = '';
                        $shipmentTracks[$shipmentItem['parent_id']]['title'] = '';
                        $shipmentTracks[$shipmentItem['parent_id']]['carrier_code'] = '';
                    }
                    $shipmentData[$shipmentItem['order_item_id']][] = array_merge([
                        'qty' => $shipmentItem['qty'],
                        'sku' => $shipmentItem['sku']
                    ], $shipmentTracks[$shipmentItem['parent_id']]);
                }
            }
        }
        $orderData['shipmentTracking'] = $shipmentData;

        /** Set payment data */
        $paymentData = $order->getPayment()->getData();
        $orderData['paymentInfo'] = $paymentData;

        $this->eventManager->dispatch(
            'wesupply_order_gather_info_after',
            ['orderData' => $orderData]
        );

        $orderData = $this->mapFieldsForWesupplyStructure($orderData);

        return $orderData;

    }

    /**
     * Prepares the order information for db storage
     * @param array $orderData
     * @return string
     */
    public function prepareForStorage($orderData)
    {
        $orderInfo = $this->convertInfoToXml($orderData);
        return $orderInfo;
    }

    /**
     * Returns the order last updated time
     * @param array $orderData
     * @return string
     */
    public function getUpdatedAt($orderData)
    {
        return $orderData['OrderModified'];
//        return $orderData['WesupplyUpdatedAt'];
//        return $orderData['wesupply_updated_at'];
//        return $orderData['updated_at'];
    }

    /**
     * Return the store id from the order information array
     * @param array $orderData
     * @return int
     */
    public function getStoreId($orderData)
    {
        return $orderData['StoreId'];
        //return $orderData['store_id'];
    }


    /**
     * @param $date
     * @return false|string
     */
    protected function modifyToLocalTimezone($date)
    {
        $formatedDate = '';

        if($date){
            try{
                $formatedCreatedAt = $this->timezone->formatDateTime($date,\IntlDateFormatter::SHORT,\IntlDateFormatter::MEDIUM);
                $createdTimestamp = strtotime($formatedCreatedAt);
                $formatedDate = date('Y-m-d H:i:s', $createdTimestamp);
            }catch(\Exception $e){
                $this->logger->error("WeSupply Error when changing date to local timezone:".$e->getMessage());
                return $formatedDate;
            }

        }

        return $formatedDate;
    }

    /**
     * Return the final parsed and mapped information only to wesupply
     * @param array $orderData
     * @return array
     */
    protected function mapFieldsForWesupplyStructure($orderData)
    {
        $updatedAt =  $this->modifyToLocalTimezone($orderData['updated_at']);
        if(!$updatedAt){
            $updatedAt =  $this->modifyToLocalTimezone(date('Y-m-d H:i:s'));
        }

        $createdAt = $this->modifyToLocalTimezone($orderData['created_at']);
        if(!$createdAt){
            $createdAt = $this->modifyToLocalTimezone(date('Y-m-d H:i:s'));
        }

        $finalOrderData = [];
        $finalOrderData['StoreId'] = $orderData['store_id'];
        $finalOrderData['LastModifiedDate'] = $updatedAt;
        $finalOrderData['OrderModified'] = $orderData['wesupply_updated_at'];
        //$finalOrderData['OrderModified'] = $this->modifyToLocalTimezone($orderData['wesupply_updated_at']);
        $finalOrderData['OrderPaymentTypeId'] = '';
        $finalOrderData['OrderID'] = self::PREFIX.$orderData['entity_id'];
        $finalOrderData['OrderNumber'] = $orderData['increment_id'];
        $finalOrderData['OrderContact'] = $orderData['shippingAddressInfo']['firstname'] . ' '
            . $orderData['shippingAddressInfo']['lastname'];
        $finalOrderData['FirstName'] = $orderData['shippingAddressInfo']['firstname'];
        $finalOrderData['LastName'] = $orderData['shippingAddressInfo']['lastname'];
        $finalOrderData['OrderDate'] = $createdAt;
        $finalOrderData['OrderAmount'] = $orderData['base_subtotal'];
        $finalOrderData['OrderAmountShipping'] = $orderData['base_shipping_amount'];
        $finalOrderData['OrderAmountTax'] = $orderData['base_tax_amount'];
        $finalOrderData['OrderAmountTotal'] = $orderData['base_grand_total'];
        $finalOrderData['OrderAmountCoupon'] = number_format(0, 4,'.','');
        // enterprise specific
        if (isset($orderData['base_gift_card_amount'])) {
            $finalOrderData['OrderAmountGiftCard'] = $orderData['base_gift_card_amount'];
        } else {
            $finalOrderData['OrderAmountGiftCard'] = 0;
        }
        $finalOrderData['OrderShippingAddress1'] = $orderData['shippingAddressInfo']['street'];
        $finalOrderData['OrderShippingCity'] = $orderData['shippingAddressInfo']['city'];
        $finalOrderData['OrderShippingStateProvince'] = $orderData['shippingAddressInfo']['region'];
        $finalOrderData['OrderShippingZip'] = $orderData['shippingAddressInfo']['postcode'];
        $finalOrderData['OrderShippingPhone'] = $orderData['shippingAddressInfo']['telephone'];
        $finalOrderData['OrderShippingCountry'] = $this->getCountryName($orderData['shippingAddressInfo']['country_id']);
        $finalOrderData['OrderShippingCountryCode'] = $orderData['shippingAddressInfo']['country_id'];
        $finalOrderData['OrderPaymentType'] = $orderData['paymentInfo']['additional_information']['method_title'];
        $finalOrderData['OrderDiscountDetailsTotal'] = $orderData['base_discount_amount'];
        $finalOrderData['OrderExternalOrderID'] = $orderData['increment_id'];

        $orderStatusInfo = $this->mapOrderStateToWeSupply($orderData);
        $finalOrderData['OrderStatus'] = $orderStatusInfo['OrderStatus'];
        $finalOrderData['OrderStatusId'] = $orderStatusInfo['OrderStatusId'];


        /** Check if customer is logged in get data from there otherwise from billing and shipping infromation */
        $finalOrderData['OrderCustomer']['CustomerName'] = $orderData['billingAddressInfo']['firstname'] . ' '
            . $orderData['billingAddressInfo']['lastname'];
        /**
         * if customer checks out as guest,we are generating a random id always begining with 100 followed by timestamp
         */
        $finalOrderData['OrderCustomer']['CustomerID'] = $orderData['customer_id'] ?? intval(664616765 . '' . $orderData['entity_id']); // mage(hex) + order id
        $finalOrderData['OrderCustomer']['CustomerFirstName'] = $orderData['billingAddressInfo']['firstname'];
        $finalOrderData['OrderCustomer']['CustomerLastName'] = $orderData['billingAddressInfo']['lastname'];
        $finalOrderData['OrderCustomer']['CustomerPostalCode'] = $orderData['billingAddressInfo']['postcode'];
        $finalOrderData['OrderCustomer']['CustomerEmail'] = $orderData['billingAddressInfo']['email'];

        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressID'] = $orderData['shippingAddressInfo']['entity_id'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressContact'] = $orderData['shippingAddressInfo']['firstname'] . ' ' . $orderData['shippingAddressInfo']['lastname'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressAddress1'] = $orderData['shippingAddressInfo']['street'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressCity'] = $orderData['shippingAddressInfo']['city'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressState'] = $orderData['shippingAddressInfo']['region'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressZip'] = $orderData['shippingAddressInfo']['postcode'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressCountry'] = $this->getCountryname($orderData['shippingAddressInfo']['country_id']);
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressCountryCode'] = $orderData['shippingAddressInfo']['country_id'];
        $finalOrderData['OrderCustomer']['CustomerShippingAddresses']['CustomerShippingAddress']['AddressPhone'] = $orderData['shippingAddressInfo']['telephone'];


        $orderItems = $this->prepareOrderItems($orderData);

        /**
         * if we only have virtual or downloadable products in order, we are not updating wesupply_orders table
         */
        if(count($orderItems)==0)
        {
            return false;
        }

        $finalOrderData['OrderItems'] = $orderItems;

        $this->eventManager->dispatch(
            'wesupply_order_mapping_info_after',
            [
                'initialOrderData' => $orderData,
                'finalOrderData' => $finalOrderData
            ]
        );

        return $finalOrderData;
    }


    /**
     * Converts order information
     * @param $orderData
     * @return mixed
     */
    protected function convertInfoToXml($orderData)
    {
        $xmlData = $this->array2xml($orderData, false);
        $xmlData = str_replace("<?xml version=\"1.0\"?>\n", '', $xmlData);

        return $xmlData;
    }

    /**
     * Convert array to xml
     * @param $array
     * @param bool $xml
     * @param string $xmlAttribute
     * @return mixed
     */
    private function array2xml($array, $xml = false, $xmlAttribute = '')
    {
        if ($xml === false) {
            $xml = new \SimpleXMLElement('<Order/>');
        }

        foreach ($array as $key => $value) {
            /**
             *  had to comment out str_replace because there is a field in Wesupply that uses an underscore (_)
             *  Field Name: Item_CouponAmount
             */
            //$key = str_replace("_", "", ucwords($key, '_'));
            $key = ucwords($key, '_');
            if (is_object($value)) continue;
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $this->array2xml($value, $xml->addChild($key), $key);
                } else {
                    //mapping for $key to proper
                    $xmlAttribute = $this->mapXmlAttributeForChildrens($xmlAttribute);
                    $this->array2xml($value, $xml->addChild($xmlAttribute), $key);
                }
            } else {
                if (is_numeric($key)) {
                    $child = $xml->addChild($xmlAttribute);
                    $child->addAttribute('key', $key);
                    $value = str_replace(['&','<','>'],['&amp;', '&lt;', '&gt;'], $value);
                    $child->addAttribute('value', $value);
                } else {
                    $value = str_replace(['&','<','>'],['&amp;', '&lt;', '&gt;'], $value);
                    $xml->addChild($key, $value);
                }
            }
        }

        return $xml->asXML();
    }

    /**
     * @param $key
     * @return mixed
     */
    private function mapXmlAttributeForChildrens($key)
    {
        $mappings = [
            'OrderItems' => 'Item',
            'AttributesInfo' => 'Info'
        ];

        if (isset($mappings[$key])) {
            return $mappings[$key];
        }

        return $key;
    }

    /**
     * Return country name
     * @param $countryId
     * @return string
     */
    protected function getCountryName($countryId)
    {
        $country = $this->countryFactory->create()->loadByCode($countryId);
        return $country->getName();
    }

    /**
     * @param $orderData
     * @return array
     * due to posibility of endless order statuses in magento2, we are transfering the order status label and order state mapped to WeSupply order status
     */
    protected function mapOrderStateToWeSupply($orderData)
    {

        $orderStatusId = '';
        $state = $orderData['state'];
        if(array_key_exists($state, $this->weSupplyStatusMappedArray))
        {
            $orderStatusId = $this->weSupplyStatusMappedArray[$state];
        }

        $statusInfo = [
            'OrderStatus' => $this->orderStatusLabel,
            'OrderStatusId' => $orderStatusId
        ];

        return $statusInfo;

    }


    /**
     * @param $status
     * @return array
     */
    protected function getItemStatusInfo($status)
    {
        switch ($status) {
            case 'canceled':
                $orderStatus = 'Canceled';
                $orderStatusId = 1;
                break;
            case 'refunded':
                $orderStatus = 'Refunded';
                $orderStatusId = 2;
                break;
            case 'shipped':
                $orderStatus = 'Shipped';
                $orderStatusId = 3;
                break;
            default:
                $orderStatus = 'Processing';
                $orderStatusId = 4;
                break;

        }

        $statusInfo = [
            'ItemStatus' => $orderStatus,
            'ItemStatusId' => $orderStatusId
        ];

        return $statusInfo;
    }


    /**
     * @param $orderData
     * @return array
     */
    protected function prepareOrderItems($orderData)
    {
        $orderItems = [];

        $itemFeeShipping = $orderData['base_shipping_amount'];

        foreach ($orderData['OrderItems'] as $item) {
            /**
             * we exclude downlodable and virtual products to be sent to WeSupply
             */
            if(in_array($item['product_type'], self::EXCLUDED_ITEMS))
            {
                continue;
            }
            $addedToShippment = false;
            $generalData = [];
            $generalData['ItemID'] = $item['item_id'];
            $generalData['ItemLevelSupplierName'] = $orderData['store_id'];
            $generalData['ItemPrice'] = $item['base_price'];
            $generalData['ItemAddressID'] = $orderData['shippingAddressInfo']['entity_id'];
            $generalData['Option1'] = '';
            $generalData['Option2'] = $this->_fetchProductOptionsAsArray($item);
            $generalData['Option3'] = $this->_fetchProductBundleOptionsAsArray($item);
            $generalData['ItemProduct'] = [];
            $generalData['ItemProduct']['ProductID'] = $item['product_id'];
            $generalData['ItemProduct']['ProductCode'] = $item['name'];
            $generalData['ItemProduct']['ProductPartNo'] = $item['sku'];
            $generalData['ItemTitle'] = $item['name'];
            /**
             * new field added ItemImageUri
             */
            $generalData['ItemImageUri'] = $this->_fetchProductImage($item);

            $qtyOrdered = intval($item['qty_ordered']);
            $qtyCanceled = intval(isset($item['qty_canceled']) ? $item['qty_canceled'] : 0);
            $qtyRefunded = intval(isset($item['qty_refunded']) ? $item['qty_refunded'] : 0);
            $qtyShipped = intval(isset($item['qty_canceled']) ? $item['qty_canceled'] : 0);
            $qtyProcessing = $qtyOrdered - $qtyCanceled - $qtyRefunded - $qtyShipped;

            $itemTotal = $item['base_row_total'];
            $taxTotal = $item['base_tax_amount'];
            $discountTotal = $item['base_discount_amount'];


            /** Send information about shipped items */
            foreach ($orderData['shipmentTracking'] as $itemId => $shipment) {
                if ($itemId == $item['item_id']) {
                    foreach ($shipment as $trackingInformation) {
                        $itemInfo = $this->getItemSpecificInformation($itemFeeShipping, $itemTotal, $taxTotal, $discountTotal, $qtyOrdered,
                            $trackingInformation['qty'], 'shipped',
                            $trackingInformation['title'], $trackingInformation['track_number']);
                        $generalData = array_merge($generalData, $itemInfo);
                        $orderItems[] = $generalData;
                        $addedToShippment = true;
                    }
                }
            }

            if ($qtyCanceled && !$addedToShippment) {
                $itemInfo = $this->getItemSpecificInformation($itemFeeShipping, $itemTotal, $taxTotal, $discountTotal, $qtyOrdered,
                    $qtyCanceled, 'canceled');
                $generalData = array_merge($generalData, $itemInfo);
                $orderItems[] = $generalData;
            }

            /** For more detailed data we might use information  from teh created credit memos */
            if ($qtyRefunded  && !$addedToShippment) {
                $itemInfo = $this->getItemSpecificInformation($itemFeeShipping, $itemTotal, $taxTotal, $discountTotal, $qtyOrdered,
                    $qtyRefunded, 'refunded');
                $generalData = array_merge($generalData, $itemInfo);
                $orderItems[] = $generalData;
            }

            /** Send information about items still in processed state */
            if ($qtyProcessing > 0  && !$addedToShippment) {
                $itemInfo = $this->getItemSpecificInformation($itemFeeShipping, $itemTotal, $taxTotal, $discountTotal, $qtyOrdered,
                    $qtyProcessing, '');
                $generalData = array_merge($generalData, $itemInfo);
                $orderItems[] = $generalData;
            }

            $itemFeeShipping = 0;
        }

        return $orderItems;
    }

    /**
     * @param $itemFeeShipping
     * @param $itemTotal
     * @param $taxTotal
     * @param $discountTotal
     * @param $qtyOrdered
     * @param $qty
     * @param $status
     * @param string $shippingService
     * @param string $shippingTracking
     * @return array
     */
    protected function getItemSpecificInformation($itemFeeShipping = 0, $itemTotal, $taxTotal, $discountTotal, $qtyOrdered, $qty, $status, $shippingService = '', $shippingTracking = '')
    {
        $information = [];

        $information['ItemQuantity'] = $qty;
        $information['ItemShippingService'] = $shippingService;
        /**
         * new field added ItemPOShipper
         */
        $information['ItemPOShipper'] = $shippingService;
        $information['ItemShippingTracking'] = $shippingTracking;
        $information['ItemTotal'] = number_format(($qty * $itemTotal) / $qtyOrdered, 4,'.','');
        $information['ItemTax'] = number_format(($qty * $taxTotal) / $qtyOrdered, 4,'.','');
        $information['Item_CouponAmount'] = number_format(($qty * $discountTotal) / $qtyOrdered, 4,'.','');
        $statusInfo = $this->getItemStatusInfo($status);
        $information['ItemStatus'] = $statusInfo['ItemStatus'];
        $information['ItemStatusId'] = $statusInfo['ItemStatusId'];
        /**
         *  new fields added
         *   ItemShipping -  the first item will have shipping value, all other items will have 0 value
         *   Item_CouponAmount - will always have 0, the discount amount is set trough OrderDiscountDetailsTotal field
         */
        $information['ItemShipping'] = number_format($itemFeeShipping, 4,'.','');
        $information['Item_CouponAmount'] = number_format(0,4,'.','');

        return $information;
    }


    private function _fetchProductBundleOptionsAsArray($item)
    {
        $bundleArray =  array();
        /**
         * bundle product options
         */
        $productOptions = $item['product_options'];
        if (isset($productOptions['bundle_options'])) {
            foreach($productOptions['bundle_options'] AS $bundleOptions)
            {
                $bundleProductInfo = array();
                $bundleProductInfo['label'] = $bundleOptions['label'];
                $finalOptionsCounter = 0;
                foreach($bundleOptions['value'] AS $finalOptions)
                {
                    $bundleProductInfo['product_'.$finalOptionsCounter] = $finalOptions;
                    $finalOptionsCounter++;

                }
                $bundleArray['value_'.$bundleOptions['option_id']] = $bundleProductInfo;

            }

        }

       return $bundleArray;

    }


    /**
     * @param $item
     * @return array
     */
    private function _fetchProductOptionsAsArray($item)
    {
        $optionsArray = array();
        /**
         * configurable product options
         */
        $productOptions = $item['product_options'];
        if (isset($productOptions['attributes_info'])) {
            foreach ($productOptions['attributes_info'] as $attributes) {
                $xmlLabel = preg_replace('/[^\w0-1]|^\d/','_',trim($attributes['label']));
                $optionsArray[$xmlLabel] = $attributes['value'];
            }
        }

        /**
         * custom options
         */
        if (isset($productOptions['options'])) {
            foreach($productOptions['options'] as $customOption)
            {
                $xmlLabel = preg_replace('/[^\w0-1]|^\d/','_',trim($customOption['label']));
                $optionsArray[$xmlLabel] = $customOption['value'];
            }
        }

        return $optionsArray;
    }


    /**
     * @param $item
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * we are fetching item image (if we have a configurable product order, and the configurable item does not have an image, we are taking the main product image instead
     */
    private function _fetchProductImage($item)
    {

        $imageUrl = '';
        $productOptions = $item['product_options'];
        if ( (isset($productOptions['simple_sku'])) ) {
            $product = $this->productRepositoryInterfaceFactory->create()->get($productOptions['simple_sku']);
            $prdImage = $product->getImage();

            if(empty($prdImage))
            {
                $product = $this->productRepositoryInterfaceFactory->create()->getById($item['product_id']);
                $prdImage = $product->getImage();
            }

        }
        else
        {
            $product = $this->productRepositoryInterfaceFactory->create()->getById($item['product_id']);
            $prdImage = $product->getImage();
        }


        if($prdImage){
            $imageUrl = $this->mediaUrl.self::PRODUCT_IMAGE_SUBDIRECTORY.$prdImage;
        }


        return $imageUrl;

    }

}
