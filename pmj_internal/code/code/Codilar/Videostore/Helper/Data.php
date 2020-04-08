<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 1/7/19
 * Time: 4:35 PM
 */

namespace Codilar\Videostore\Helper;

use Codilar\MasterTables\Model\MetalBomRepository;
use Codilar\MasterTables\Model\MetalRepository;
use Codilar\MasterTables\Model\StoneBomRepository;
use Codilar\ProductImport\Helper\Data as ProductImportHelper;
use Magento\Framework\App\Helper\Context;
use Magento\InventoryApi\Api\GetSourceItemsBySkuInterface;

/**
 * Class Data
 * @package Codilar\Videostore\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const ZERO = 0;
    /**
     * @var ProductImportHelper
     */
    private $parseData;
    /**
     * @var StoneBomRepository
     */
    private $stoneBomRepository;
    /**
     * @var MetalRepository
     */
    private $metalRepository;
    /**
     * @var GetSourceItemsBySkuInterface
     */
    private $getSourceItemsBySkuInterface;
    /**
     * @var StoneBomRepository
     */
    private $metalBomRepository;

    /**
     * Data constructor.
     * @param Context $context
     * @param ProductImportHelper $parseData
     * @param MetalRepository $metalRepository
     * @param StoneBomRepository $stoneBomRepository
     * @param StoneBomRepository $metalBomRepository
     * @param GetSourceItemsBySkuInterface $getSourceItemsBySku
     */
    public function __construct(
        Context $context,
        ProductImportHelper $parseData,
        MetalRepository $metalRepository,
        StoneBomRepository $stoneBomRepository,
        MetalBomRepository $metalBomRepository,
        GetSourceItemsBySkuInterface $getSourceItemsBySku
    ) {
        parent::__construct($context);
        $this->parseData=$parseData;
        $this->metalRepository=$metalRepository;
        $this->stoneBomRepository=$stoneBomRepository;
        $this->metalBomRepository=$metalBomRepository;
        $this->getSourceItemsBySkuInterface=$getSourceItemsBySku;
    }

    /**
     * @param $currentProduct
     * @return array
     */
    public function getStoneAndMetalBomVariantsDetails($currentProduct)
    {
        $StoneAndMetalBomVariantsDetails=[];
        $stoneVariantName=[];
        $metalVariantName=[];
        $stoneWeight=[];
        $metalWeight=[];
        $stonePcs=[];
        $StoneAndMetalBomVariantsDetails['metal_weight']=null;
        $StoneAndMetalBomVariantsDetails['stone_weight']=null;
        $StoneAndMetalBomVariantsDetails['stone_variant_name']=null;
        $StoneAndMetalBomVariantsDetails['stone_pcs']=null;
        $currentProductDetails= $this->parseData->getBomVariantDeatails($currentProduct);
        if ($currentProductDetails) {
            foreach ($currentProductDetails as $productDetail) {
                if ($productDetail['stone_pcs']== self::ZERO) {
//                    $StoneAndMetalBomVariantsDetails['metal_weight']=$StoneAndMetalBomVariantsDetails['metal_weight']+$productDetail['stone_weight'];
//                    $StoneAndMetalBomVariantsDetails['metal_weight']=$productDetail['stone_weight'];
                    array_push($metalWeight, $productDetail['stone_weight']);
                    if ($productDetail['bom_variant_name']) {
                        array_push($metalVariantName, $productDetail['bom_variant_name']);
                    }
                } else {
                    array_push($stoneWeight, $productDetail['stone_weight']);
                    array_push($stonePcs, $productDetail['stone_pcs']);
                    if ($productDetail['bom_variant_name']) {
                        array_push($stoneVariantName, $productDetail['bom_variant_name']);
                    }
                }
            }
        }
        $StoneAndMetalBomVariantsDetails['stone_pcs']=$stonePcs;
        $StoneAndMetalBomVariantsDetails['stone_weight']=$stoneWeight;
        $StoneAndMetalBomVariantsDetails['metal_weight']=$metalWeight;
        $StoneAndMetalBomVariantsDetails['stone_variant_name']=$stoneVariantName;
        $StoneAndMetalBomVariantsDetails['metal_variant_name']=$metalVariantName;
        return $StoneAndMetalBomVariantsDetails;
    }

    /**
     * @param $currentProduct
     * @return mixed
     */
    public function getMetalColor($currentProduct)
    {
        $collection=$this->metalRepository->getByKaratColor($currentProduct->getKaratColor());
        if ($collection->getData()) {
            $metalColor=$collection->getData()['metal_color'];
            return $metalColor;
        } else {
            return null;
        }
    }

    /**
     * Get Metal Karet
     * @param $currentProduct
     * @return |null
     */
    public function getMetalKarat($currentProduct)
    {
        $collection=$this->metalRepository->getByKaratColor($currentProduct->getKaratColor());
        if ($collection->getData()) {
            $metalColor=$collection->getData()['karat'];
            return $metalColor;
        } else {
            return null;
        }
    }

    /**
     * Get Stone Details
     * @param $currentProduct
     * @param $stoneVariantNames
     * @return array
     */
    public function getStoneDetails($currentProduct, $stoneVariantNames)
    {
        $stoneDetails=[];
        $stoneDetails['stone_type']=null;
        $stoneDetails['stone_shape']=null;
        $stoneDetails['stone_quality']=null;
        $stoneDetails['stone_color']=null;
        $stoneType=[];
        $stoneShape=[];
        $stoneQuality=[];
        $stoneColor=[];
        if ($stoneVariantNames) {
            foreach ($stoneVariantNames as $stoneVariantName) {
                $stoneBOMData=$this->stoneBomRepository->getCollection()->addFieldToFilter('stone_bom_variant', $stoneVariantName)->getData();
                if ($stoneBOMData) {
                    array_push($stoneType, $stoneBOMData[0]['stone_type']);
                    array_push($stoneShape, $stoneBOMData[0]['stone_shape']);
                    array_push($stoneQuality, $stoneBOMData[0]['stone_quality']);
                    array_push($stoneColor, $stoneBOMData[0]['stone_color']);
                } else {
                    array_push($stoneType, null);
                    array_push($stoneShape, null);
                    array_push($stoneQuality, null);
                    array_push($stoneColor, null);
                }
            }
            $stoneDetails['stone_type']=$stoneType;
            $stoneDetails['stone_shape']=$stoneShape;
            $stoneDetails['stone_quality']=$stoneQuality;
            $stoneDetails['stone_color']=$stoneColor;
        }

        return $stoneDetails;
    }


    /**
     * Get Metal Details
     * @param $currentProduct
     * @param $metalVariantNames
     * @return array
     */
    public function getMetalDetails($currentProduct, $metalVariantNames)
    {
        $metalDetails=[];
        $metalDetails['metal_color']=null;
        $metalDetails['metal_karat']=null;
        $metalDetails['metal_type']=null;
        $metalColor=[];
        $metalKarat=[];
        $metalType=[];
        if ($metalVariantNames) {
            foreach ($metalVariantNames as $metalVariantName) {
                $stoneBOMData=$this->metalBomRepository->getCollection()->addFieldToFilter('metal_bom_variant', $metalVariantName)->getData();
                if ($stoneBOMData) {
                    array_push($metalColor, $stoneBOMData[0]['color']);
                    array_push($metalKarat, $stoneBOMData[0]['purity']);
                    array_push($metalType, $stoneBOMData[0]['item_code']);
                } else {
                    array_push($metalColor, null);
                    array_push($metalKarat, null);
                    array_push($metalType, null);
                }
            }
            $metalDetails['metal_color']=$metalColor;
            $metalDetails['metal_karat']=$metalKarat;
            $metalDetails['metal_type']=$metalType;
        }
        return $metalDetails;
    }

    /**
     * @param $sku
     * @return int
     */
    public function getStockStatus($sku)
    {
        $sourceItemsBySku = $this->getSourceItemsBySkuInterface->execute($sku);
        foreach ($sourceItemsBySku as $sourceItem) {
            $source_qty = $sourceItem->getQuantity();
        }
        if ($source_qty==self::ZERO) {
            return 0;
        } else {
            return 1;
        }
    }
}
