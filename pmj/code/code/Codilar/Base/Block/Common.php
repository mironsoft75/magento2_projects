<?php

namespace Codilar\Base\Block;

use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Directory\Model\Currency;
use \Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Codilar\StoneAndMetalRates\Model\StoneAndMetalRatesFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Common
 * @package Codilar\Base\Block
 */
class Common extends Template{
    CONST METAL_TYPE = "Platinum";
    /**
     * @var Registry
     */
    private $_registry;
    /**
     * @var StoneAndMetalRatesFactory
     */
    private $_stoneAndMetalRatesFactory;
    /**
     * @var ProductRepositoryInterface
     */
    private $_productRepository;
    /**
     * @var Currency
     */
    protected $_currency;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Common constructor.
     * @param StoneAndMetalRatesFactory $stoneAndMetalRatesFactory
     * @param Template\Context $context
     * @param Registry $registry
     * @param ProductRepositoryInterface $productRepository
     * @param Currency $currency
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        StoneAndMetalRatesFactory $stoneAndMetalRatesFactory,
        Template\Context $context,
        Registry $registry,
        ProductRepositoryInterface $productRepository,
        Currency $currency,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->_currency = $currency;
        $this->_registry = $registry;
        $this->_stoneAndMetalRatesFactory = $stoneAndMetalRatesFactory;
        $this->_productRepository = $productRepository;
        $this->_storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getCategoryName(){
        try{
            $category = $this->_registry->registry('current_category');
            if($category){
                $categoryData = $category->getData();
                if(array_key_exists('title_rewrite',$categoryData)){
                    if(strlen($categoryData['title_rewrite']) > 0){
                        return $categoryData['title_rewrite'];
                    }
                }
                return $categoryData['name'];
            }
        }
        catch (\Exception $e){
            $this->_logger->info("Error while getting category name ".$e->getMessage());
        }
        return "";
    }

    /**
     * @return \Magento\Catalog\Api\Data\ProductInterface|mixed|null
     */
    public function getCurrentProduct(){
        try{
            $productId = $this->_registry->registry('simple_configurable_product_id');
            if($productId){
                $product = $this->_productRepository->getById($productId);
                $this->_registry->unregister('simple_configurable_product_id');
            }
            else{
                $product = $this->_registry->registry('current_product');
            }
            return $product;
        }
        catch (\Exception $e){
            $this->_logger->info($e->getMessage());
            return null;
        }
    }
    /**
     * @param $product
     * @return array
     */
    public function getDiamondDetails($product){
        try{
            $diamondPrice = 0;
            $allStoneDetails = [];
            /**
             * @var $stoneRate \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates
             */
            $stoneRate = $this->_stoneAndMetalRatesFactory->create();
            $stoneInformation = $product->getStoneInformation();
            if($stoneInformation) {
                $stones = explode('},', $stoneInformation);
                $stonesExploded = explode('{', $stones[0]);
                if(count($stonesExploded) > 1){
                    $stones[0] = $stonesExploded[1];
                    $emptyValues = ['na', 'no'];
                    $stoneDetails = $this->removeBrackets($stones);
                    $type = 'stone';
                    foreach($stoneDetails as $individualStones) {
                        $equalSeperated = $this->getEqualSeperatedValue($individualStones,$emptyValues);
                        $internalStoneName = $equalSeperated['internal_stone_name'];
                        if(!$equalSeperated['stone_rate'])  {
                            $stonePrice = $stoneRate->getRateByName($internalStoneName,$type,1);
                            $equalSeperated['stone_rate'] = $stonePrice;
                            $individualStonesPrice = $stonePrice * $equalSeperated['stone_total_wt'];
                            $diamondPrice = $diamondPrice + $individualStonesPrice;
                        }else{
                            $individualStonesPrice = $equalSeperated['stone_rate']*$equalSeperated['stone_total_wt'];
                            $diamondPrice = $diamondPrice + $individualStonesPrice;
                        }
                        $equalSeperated['stone_total_price'] = $individualStonesPrice;
                        $allStoneDetails[] = $equalSeperated;
                    }
                    $allStoneDetails['diamond_price'] = $diamondPrice;
                    return $allStoneDetails;
                }
            }
        }
        catch (\Exception $e){
            $this->_logger->info("Error while getting diamond details ".$e->getMessage());
        }
        return null;
    }

    /**
     * @param $product
     * @return array
     */
    public function getGoldDetails($product){
        try{
            /**
             * @var $metalRate \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates
             */
            $metalRate = $this->_stoneAndMetalRatesFactory->create();
            $metalNetWeight = $product->getNetWeight();
            if($metalNetWeight){
                $labourRate = $product->getLabourRate();
                $wastagePercentage = $product->getWastagePercentage();
                $metalType = $product->getAttributeText('metal_type');
                $metalKarat = $product->getAttributeText('metal_karat');
                $type = 'metal';
                $metalRate = $this->getMetalRates($metalType, $metalRate, $type, $metalKarat);
                $totalMetalAmount = $this->getTotalMetalAmount($metalNetWeight, $metalRate);
                $metalPrice = $totalMetalAmount
                    + $this->getLabourCharges($labourRate, $metalNetWeight)
                    + $this->getWastageAmount($totalMetalAmount, $wastagePercentage);
                $metalDetails = [
                    'metal_type' => $metalType,
                    'metal_karat' => $metalKarat,
                    'metal_rate'  => $metalRate,
                    'metal_weight'=> $metalNetWeight,
                    'metal_amount'=>$totalMetalAmount,
                    'metal_total_amount' => $metalPrice,
                    'making_charges' => $metalPrice - $totalMetalAmount
                ];
                return $metalDetails;
            }
        }
        catch (\Exception $e){
            $this->_logger->info("Error while getting gold details ".$e->getMessage());
        }
        return null;
    }

    /**
     * @param $individualStones
     * @param $emptyValues
     * @return array
     */
    public function getEqualSeperatedValue($individualStones,$emptyValues){
        $stones = explode(',',$individualStones);
        $stoneValues = [];
        foreach($stones as $stone){
            $stoneArray = explode("=",$stone);
            $stoneValues[$stoneArray[0]] = $stoneArray[1];
            if(in_array(strtolower($stoneArray[1]),$emptyValues)){
                $stoneValues[$stoneArray[0]] = null;
            }
        }
        return $stoneValues;
    }

    /**
     * @param $stones
     * @return array
     */
    public function removeBrackets($stones)
    {
        $newStones  = [];
        foreach($stones as $stone)
        {
            array_push($newStones, trim(preg_replace("/[{}]/", "", $stone)));
        }
        return $newStones;
    }

    /**
     * @param $labourRate
     * @param $metalNetWeight
     * @return float|int
     */
    public function getLabourCharges($labourRate, $metalNetWeight)
    {
        return $labourRate * $metalNetWeight;
    }

    /**
     * @param $totalMetalAmount
     * @param $wastagePercentage
     * @return float|int
     */
    public function getWastageAmount($totalMetalAmount, $wastagePercentage)
    {
        return ($totalMetalAmount * $wastagePercentage) / 100;
    }

    /**
     * @param $metalNetWeight
     * @param $metalRate
     * @return float|int
     */
    public function getTotalMetalAmount($metalNetWeight, $metalRate)
    {
        return $metalNetWeight * $metalRate;
    }

    /**
     * @param $metalType
     * @param $metalRate
     * @param $type
     * @param $metalKarat
     * @return float|int
     */
    public function getMetalRates($metalType, $metalRate, $type, $metalKarat)
    {
        switch ($metalType) {
            case self::METAL_TYPE:
                $metalRate = $metalRate->getRateByName($metalType, $type, 1);
                break;
            default:
                $metalKarat = $metalType . '_' . $metalKarat;
                $metalRate = $metalRate->getRateByName($metalKarat, $type, 1);
                break;
        }
        return $metalRate;
    }
    /**
     * Get currency symbol for current locale and currency code
     *
     * @return string
     */
    public function getCurrentCurrencySymbol()
    {
        try{
            $currencySymbol = $this->_storeManager->getStore()->getCurrentCurrency()->getCurrencySymbol();
            if(!$currencySymbol){
                return $this->_currency->getCurrencySymbol();
            }
            return $currencySymbol;
        }
        catch (\Exception $e){
            $this->_logger->info($e->getMessage());
        }
    }

    /**
     * @param $productSku
     * @return null
     */
    public function getMetalKarat($productSku){
        try{
            $product = $this->_productRepository->get($productSku,false);
            $metalKarat = $product->getAttributeText('metal_karat');
            return $metalKarat;
        }
        catch (\Exception $e){
            return null;
        }
    }
}