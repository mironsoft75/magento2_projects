<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/

namespace Codilar\PriceCalculation\Helper;

use Codilar\StoneAndMetalRates\Model\StoneAndMetalRatesFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;

/**
 * Class Data
 * @package Codilar\PriceCalculation\Helper
 */
class Data extends AbstractHelper
{

    const METAL_TYPE = "Platinum";
    const METAL = "metal";
    const STONE = "stone";

    /**
     * @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory
     */
    protected $_attributeSetCollection;
    /**
     * @var StoneAndMetalRatesFactory
     */
    protected $_stoneAndMetalDetails;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var productRepository
     */
    protected $_productRepository;

    /**
     * ProductPricesaveafter constructor.
     * @param ProductFactory $product_details
     */
    public function __construct(
        StoneAndMetalRatesFactory $stoneAndMetalDetails,
        CollectionFactory $attributeSetCollection,
        LoggerInterface $logger
    )
    {
        $this->_stoneAndMetalDetails = $stoneAndMetalDetails;
        $this->_logger = $logger;
        $this->_attributeSetCollection = $attributeSetCollection;
    }

    /**
     * @return mixed
     */
    public function metalPriceCalculation($product)
    {
        /**
         * @var $metalRate \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates
         */
        $metalRate = $this->_stoneAndMetalDetails->create();
        $metalNetweight = $product->getNetWeight();
        $labourRate = $product->getLabourRate();
        $wastagePercentage = $product->getWastagePercentage();
        $metalType = $product->getAttributeText('metal_type');
        $metalKarat = $product->getAttributeText('metal_karat');
        $metalRate = $this->getMetalRates($metalType, $metalRate, self::METAL, $metalKarat);
        $totalMetalAmount = $this->getTotalMetalAmount($metalNetweight, $metalRate);
        $metalPrice = $totalMetalAmount
            + $this->getLabourCharges($labourRate, $metalNetweight)
            + $this->getWastageAmount($totalMetalAmount, $wastagePercentage);
        return $metalPrice;
    }

    /**
     * @param $stones
     * @return array
     */
    public function removeBrackets($stones)
    {
        $newStones = array();
        foreach ($stones as $stone) {
            array_push($newStones, trim(preg_replace("/[{}]/", "", $stone)));
        }
        return $newStones;
    }

    /**
     * @return int
     */
    public function stonePriceCalculation($product)
    {
        /**
         * @var $stoneRate \Codilar\StoneAndMetalRates\Model\StoneAndMetalRates
         */
        $stoneRate = $this->_stoneAndMetalDetails->create();
        $stoneInformation = $product->getStoneInformation();
        if (!$stoneInformation) {
            $diamondPrice = 0;
        } else {
            $diamondPrice = $this->getDiamondPrice($stoneRate, $stoneInformation);
        }
        return $diamondPrice;
    }

    /**
     * @param $stoneRate
     * @param $stoneInformation
     * @return float|int
     */
    public function getDiamondPrice($stoneRate, $stoneInformation)
    {
        $diamondPrice = 0;
        $stones = explode('},', $stoneInformation);
        if (count(explode('{', $stones[0])) >= 1) {
            $stones[0] = explode('{', $stones[0])[1];
            $emptyValues = ['na', 'no'];
            $stoneDetails = $this->removeBrackets($stones);
            foreach ($stoneDetails as $individual_stones) {
                $stones = explode(',', $individual_stones);
                foreach ($stones as $stone) {
                    $res = explode('=', $stone);
                    $equalSeperated[$res[0]] = in_array(strtolower($res[1]), $emptyValues) ? NULL : $res[1];
                }
                if ($equalSeperated['internal_stone_name'] && $equalSeperated['stone_total_wt']) {
                    if (!$equalSeperated['stone_rate']) {
                        $stonePrice = $stoneRate->getRateByName($equalSeperated['internal_stone_name'], self::STONE, 1);
                        $diamondPrice = $diamondPrice + $stonePrice * $equalSeperated['stone_total_wt'];

                    } else {
                        $diamondPrice = $diamondPrice + $equalSeperated['stone_rate'] * $equalSeperated['stone_total_wt'];
                    }
                }
            }
        }
        return $diamondPrice;
    }

    /**
     * @param $attrSetName
     * @return int
     */
    public function getAttrSetId($attrSetName)
    {
        /**
         * @var $attributeSet \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection
         */
        $attributeSet = $this->_attributeSetCollection->create()->addFieldToSelect(
            '*'
        )->addFieldToFilter(
            'attribute_set_name',
            $attrSetName
        );
        foreach ($attributeSet as $attr):
            $attributeSetId = $attr->getAttributeSetId();
        endforeach;
        return $attributeSetId;
    }

    /**
     * @param $labourRate
     * @param $metalNetweight
     * @return float|int
     */
    public function getLabourCharges($labourRate, $metalNetweight)
    {
        return $labourRate * $metalNetweight;
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
     * @param $metalNetweight
     * @param $metalRate
     * @return float|int
     */
    public function getTotalMetalAmount($metalNetweight, $metalRate)
    {
        return $metalNetweight * $metalRate;
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


}