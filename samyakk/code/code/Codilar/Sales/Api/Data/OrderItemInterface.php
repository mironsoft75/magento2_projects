<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;

interface OrderItemInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getOrderId();

    /**
     * @param int $orderId
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setOrderId($orderId);

    /**
     * @return int
     */
    public function getStoreId();

    /**
     * @param int $store
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setStoreId($store);

    /**
     * @return int
     */
    public function getProductId();

    /**
     * @param int $productId
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setProductId($productId);

    /**
     * @return string
     */
    public function getUrlKey();

    /**
     * @param string $urlKey
     * @return $this
     */
    public function setUrlKey($urlKey);

    /**
     * @return string
     */
    public function getSku();

    /**
     * @param string $sku
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setSku($sku);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getQtyOrdered();

    /**
     * @param int $qtyOrdered
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setQtyOrdered($qtyOrdered);

    /**
     * @return string
     */
    public function getRowTotal();

    /**
     * @param float $rowTotal
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setRowTotal($rowTotal);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setImage($image);

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface[]
     */
    public function getOptions();

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemOptionsInterface[] $options
     * @return \Codilar\Sales\Api\Data\OrderItemInterface
     */
    public function setOptions($options);

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemFormsInterface[]
     */
    public function getCustomForms();

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemFormsInterface[] $customForms
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setCustomForms($customForms);

    /**
     * @return \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface
     */
    public function getTrackingData();

    /**
     * @param \Codilar\Sales\Api\Data\OrderItemShipmentTrackInterface $trackingData
     * @return \Codilar\Sales\Api\Data\OrderInterface
     */
    public function setTrackingData($trackingData);
}
