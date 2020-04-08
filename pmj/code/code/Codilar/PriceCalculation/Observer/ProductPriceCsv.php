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

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Magento\Catalog\Model\Product\OptionFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class ProductPriceCsv
 * @package Codilar\PriceCalculation\Observer
 */
class ProductPriceCsv implements ObserverInterface
{
    const CONFIGURABLE = "configurable";
    const VISIBLITY = "4";
    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory
     */
    protected $_attributeSetCollection;
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;
    /**
     * @var \Magento\Catalog\Model\Product\Option
     */
    protected $_option;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * ProductPriceCsv constructor.
     * @param LoggerInterface $logger
     * @param ProductRepositoryInterface $productRepository
     * @param OptionFactory $option
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        LoggerInterface $logger,
        ProductRepositoryInterface $productRepository,
        OptionFactory $option,
        StoreManagerInterface $storeManager

    )
    {
        $this->_logger = $logger;
        $this->_productRepository = $productRepository;
        $this->_option = $option;
        $this->_storeManager = $storeManager;
    }

    /**
     * @param $product
     * @return array
     */
    public function getValues($product)
    {
        $values = array();
        $sortOrder = 0;
        if ($product->getAttributeText('metal_color')) {
            if (is_array($product->getAttributeText('metal_color'))) {
                foreach ($product->getAttributeText('metal_color') as $color) {
                    $values[] = [
                        'record_id' => 0,
                        'title' => $color,
                        'price' => "",
                        'price_type' => "fixed",
                        'sort_order' => $sortOrder++,
                        'is_delete' => 0
                    ];
                }
            } else {
                $values[] = [
                    'title' => $product->getAttributeText('metal_color'),
                    'price' => "",
                    'price_type' => "fixed",
                    'sort_order' => 1,
                    'is_delete' => 0
                ];
            }
        }

        return $values;

    }

    /**
     * @param $product
     * @return array
     */
    public function getSize($product)
    {
        try {
            $size = array();
            $sortOrder = 0;
            if ($product['upper_size_limit'] && $product['lower_size_limit']) {
                if ($product['upper_size_limit'] >= $product['lower_size_limit']) {
                    for ($i = $product['lower_size_limit']; $i <= $product['upper_size_limit']; $i++) {
                        $size[] = [
                            'record_id' => 0,
                            'title' => $i,
                            'price' => "",
                            'price_type' => "fixed",
                            'sort_order' => $sortOrder++,
                            'is_delete' => 0
                        ];
                    }
                }
                return $size;

            }

        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }

    }

    /**
     * @param $product
     * @return int
     */
    public function isRequired($product)
    {
        return ($product->getTypeId() == self::CONFIGURABLE) ? 1 : 0;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        try {
            if ($products = $observer->getEvent()->getBunch()) {
                foreach ($products as $productInfo) {
                    $product = $this->_productRepository->get($productInfo['sku']);
                    $this->_storeManager->setCurrentStore('0');
                    if ($product->getVisibility() == self::VISIBLITY) {
                        $values = $this->getValues($product);
                        $size = $this->getSize($product);
                        $isRequired = $this->isRequired($product);
                        if ($size) {
                            $options = [
                                [
                                    "sort_order" => 1,
                                    "title" => "metal",
                                    "price_type" => "fixed",
                                    "price" => "",
                                    "type" => "drop_down",
                                    "values" => $values,
                                    "is_require" => $isRequired

                                ], [
                                    "sort_order" => 2,
                                    "title" => "size",
                                    "price_type" => "fixed",
                                    "price" => "",
                                    "type" => "drop_down",
                                    "values" => $size,
                                    "is_require" => $isRequired
                                ]
                            ];
                        } else {
                            $options = [
                                [
                                    "sort_order" => 1,
                                    "title" => "metal",
                                    "price_type" => "fixed",
                                    "price" => "",
                                    "type" => "drop_down",
                                    "values" => $values,
                                    "is_require" => $isRequired

                                ]
                            ];
                        }

                        if (!$product->getOptions()) {
                            $product->setHasOptions(1);
                            $product->setCanSaveCustomOptions(true);
                            foreach ($options as $arrayOption) {
                                /**
                                 * @var $option \Magento\Catalog\Model\Product\Option
                                 */
                                $option = $this->_option->create()
                                    ->setProductId($product->getId())
                                    ->setStoreId($product->getStoreId())
                                    ->addData($arrayOption);
                                $option->save();
                                $product->addOption($option);
                            }
                        }
                    }
                    $this->_productRepository->save($product);
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }
}