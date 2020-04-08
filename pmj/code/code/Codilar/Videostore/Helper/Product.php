<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 17/1/19
 * Time: 5:41 PM
 */

namespace Codilar\Videostore\Helper;


use Codilar\Base\Helper\Data;
use Magento\Catalog\Helper\Product as ProductHelper;
use Magento\Catalog\Helper\Product\Compare;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Helper\Data as ConfigurableProductHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
/**
 * Class Product
 * @package Codilar\Base\Plugin
 */
class Product extends  \Magento\Framework\App\Helper\AbstractHelper
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
     * @var Data
     */
    protected $_helper;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var Compare
     */
    protected $_compareHelper;
    /**
     * @var ProductHelper
     */
    protected $_productHelper;

    /**
     * Product constructor.
     * @param Configurable $configurable
     * @param ProductRepository $productRepository
     * @param ConfigurableProductHelper $configurableProductHelper
     * @param Data $helper
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param Compare $compareHelper
     * @param ProductHelper $productHelper
     */
    public function __construct(
        Configurable $configurable,
        ProductRepository $productRepository,
        ConfigurableProductHelper $configurableProductHelper,
        Data $helper,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        Compare $compareHelper,
        ProductHelper $productHelper
    )
    {
        $this->_configurableProductInstance = $configurable;
        $this->_productRepository = $productRepository;
        $this->_configurableProductHelper = $configurableProductHelper;
        $this->_helper = $helper;
        $this->_storeManager = $storeManager;
        $this->_compareHelper = $compareHelper;
        $this->_productHelper = $productHelper;
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
     * @param $parentId
     * @return int|\Magento\Catalog\Api\Data\ProductInterface|mixed|string|null
     */
    public function getChildProductId($parentId)
    {
        try{
            $product = $this->_productRepository->getById($parentId, false, $this->getStoreId());
            $parent = null;
            $child = null;
            if ($product->getTypeId() == "configurable") { // is a configurable product
                $parent = $product;
                $simpleProducts = $this->_configurableProductInstance->getUsedProductCollection($parent);
                if(count($simpleProducts)){
                    $lowestPrices = [];
                    foreach ($simpleProducts as $simpleProduct){
                        $simpleProductRep = $this->_productRepository->getById($simpleProduct->getId(), false, $this->getStoreId());
                        $lowestPrices[$simpleProductRep->getId()] = $simpleProductRep->getFinalPrice();
                        $child = $simpleProductRep;
                    }
                    asort($lowestPrices);
                    foreach ($lowestPrices as $productId => $value){
                        $child = $productId;
                        break;
                    }
                }
            }
            return $child;
        }
        catch (\Exception $e){
            return $parentId;
        }
    }

}