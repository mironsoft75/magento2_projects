<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/

namespace Codilar\PriceCalculation\Observer;

use Magento\Framework\Event\ObserverInterface;
use Codilar\PriceCalculation\Helper\Data;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class SetCentralComputedPrice
 * @package Codilar\PriceCalculation\Observer
 */
class SetCentralComputedPrice implements ObserverInterface
{
    const DIAMOND_PRODUCT = "Diamond Product";
    const INTERNAL_STONE_NAME = "internal_stone_name";
    const CUSTOM_PRICE = "Yes";
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var productRepository
     */
    protected $_productRepository;
    /**
     * @var Data
     */
    protected $_priceHelper;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * SetCentralComputedPrice constructor.
     * @param Data $data
     * @param RequestInterface $request
     * @param LoggerInterface $logger
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Data $data,
        RequestInterface $request,
        LoggerInterface $logger,
        StoreManagerInterface $storeManager

    )
    {
        $this->_priceHelper = $data;
        $this->_logger = $logger;
        $this->request = $request;
        $this->_storeManager = $storeManager;

    }

    /**
     * @param $stoneName
     * @param $stoneCount
     * @return false|mixed|string
     */
    public function stoneName($stoneName, $stoneCount)
    {
        $stoneName = json_encode($stoneName);
        $stoneName = trim($stoneName, "[]");
        $stoneName = $stoneCount . $stoneName;
        $stoneName = str_replace(':', '=', $stoneName);
        $stoneName = str_replace('"', '', $stoneName);
        $stoneName = str_replace('"', '', $stoneName);
        return $stoneName;
    }

    /**
     * @param $product
     */
    public function stoneInformation($product)
    {
        try {
            $count = 0;
            $post = $this->request->getPost();
            $post = $post['product'];
            if (!array_key_exists(self::INTERNAL_STONE_NAME, $post)) {
                $product->setStoneInformation("");
            } else {
                $internalStoneName = $product->getData(self::INTERNAL_STONE_NAME);
                if ($internalStoneName) {
                    $stoneName = [];
                    foreach ($internalStoneName as $name) {
                        unset($name['record_id']);
                        unset($name['initialize']);
                        if ($name['internal_stone_name'] && $name['stone_total_wt']) {
                            array_push($stoneName, $name);
                            $count++;
                        }
                    }
                    if ($count) {
                        $stoneCount = "number_of_stone_type=$count,";
                        $stoneName = $this->stoneName($stoneName, $stoneCount);
                        $product->setStoneInformation($stoneName);
                    }
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $attributeId = $this->_priceHelper->getAttrSetId(self::DIAMOND_PRODUCT);
        try {
            $this->_productRepository = $observer->getProduct();
            $customPrice = $this->_productRepository->getAttributeText('custom_price');
            if ($customPrice == self::CUSTOM_PRICE) {
                $this->_storeManager->setCurrentStore('0');
                $this->stoneInformation($this->_productRepository);
                $metalPrice = $this->_priceHelper->metalPriceCalculation($this->_productRepository);
                $diamondPrice = $this->_priceHelper->stonePricecalculation($this->_productRepository);
                if ($this->_productRepository->getAttributeSetId() != $attributeId) {
                    $grandTotal = $metalPrice + $diamondPrice;
                    $this->_productRepository->setPrice($grandTotal);
                }
            }
            return $this;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }

}