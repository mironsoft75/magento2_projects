<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Model\Order;

use Codilar\Core\Helper\Product;
use Codilar\Sales\Api\Data\OrderItemInterface;
use Codilar\Sales\Api\Data\OrderItemOptionsInterface;
use Magento\Framework\Api\AttributeValueFactory;

class Item extends \Magento\Sales\Model\Order\Item implements OrderItemInterface
{
    /**
     * @var Product
     */
    private $productHelper;
    /**
     * @var \Codilar\Sales\Api\Data\OrderItemOptionsInterfaceFactory
     */
    private $orderItemOptionsInterfaceFactory;

    /**
     * Item constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Sales\Model\OrderFactory $orderFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
     * @param Product $productHelper
     * @param \Codilar\Sales\Api\Data\OrderItemOptionsInterfaceFactory $orderItemOptionsInterfaceFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     * @param \Magento\Framework\Serialize\Serializer\Json|null $serializer
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Sales\Model\OrderFactory $orderFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        Product $productHelper,
        \Codilar\Sales\Api\Data\OrderItemOptionsInterfaceFactory $orderItemOptionsInterfaceFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [],
        ?\Magento\Framework\Serialize\Serializer\Json $serializer = null
    ) {
        parent::__construct($context, $registry, $extensionFactory, $customAttributeFactory, $orderFactory, $storeManager, $productRepository, $resource, $resourceCollection, $data, $serializer);
        $this->productHelper = $productHelper;
        $this->orderItemOptionsInterfaceFactory = $orderItemOptionsInterfaceFactory;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->getProductImage();
    }

    /**
     * @param string $image
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setImage($image)
    {
        return $this->setData("image", $image);
    }

    /**
     * @return string
     *
     */
    public function getUrlKey()
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productHelper->getProduct($this->getProductId());
        return (string)$product->getUrlKey();
    }

    /**
     * @param string $urlKey
     * @return OrderItemInterface|Item
     */
    public function setUrlKey($urlKey)
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->productHelper->getProduct($this->getProductId());
        return $this->setData("url_key", (string)$product->getUrlKey());
    }

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface[]
     */
    public function getOptions()
    {
        if (!$this->getData("options")) {
            return $this->getOrderedItemOptions();
        } else {
            return $this->getData("options");
        }
    }

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemOptionsInterface[] $options
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setOptions($options)
    {
        return $this->setData("options", $options);
    }

    /**
     * @return string
     */
    protected function getProductImage()
    {
        $product = $this->productHelper->getProduct($this->getProductId());
        return (string)$this->productHelper->getImageUrl($product);
    }

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface[]
     */
    protected function getOrderedItemOptions()
    {
        $optionsArray = $this->getItemOptions();
        $options = [];
        foreach ($optionsArray as $option) {
            /** @var OrderItemOptionsInterface $itemOptions */
            $itemOptions = $this->orderItemOptionsInterfaceFactory->create();
            $options[] = $itemOptions->setLabel($option['label'])->setValue($option['value']);
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getItemOptions()
    {
        $result = [];
        $options = $this->getProductOptions();
        if ($options) {
            if (isset($options['options']) && is_array($options['options'])) {
                $result = array_merge($result, $options['options']);
            }
            if (isset($options['additional_options']) && is_array($options['additional_options'])) {
                $result = array_merge($result, $options['additional_options']);
            }
            if (isset($options['attributes_info']) && is_array($options['attributes_info'])) {
                $result = array_merge($result, $options['attributes_info']);
            }
        }
        return $result;
    }

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemFormsInterface[]
     */
    public function getCustomForms()
    {
        return $this->getData("custom_forms");
    }

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemFormsInterface[] $customForms
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setCustomForms($customForms)
    {
        return $this->setData("custom_forms");
    }

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface
     */
    public function getTrackingData()
    {
        return $this->getData("tracking_data");
    }

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface $trackingData
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setTrackingData($trackingData)
    {
        return $this->setData("tracking_data", $trackingData);
    }

    /**
     * @return string
     */
    public function getRowTotal()
    {
        return $this->getOrder()->formatPriceTxt(parent::getRowTotal());
    }
}
