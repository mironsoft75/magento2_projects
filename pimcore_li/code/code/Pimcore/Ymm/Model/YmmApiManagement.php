<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 17/9/18
 * Time: 4:07 PM
 */

namespace Pimcore\Ymm\Model;

use Magento\Eav\Api\AttributeOptionManagementInterface;
use Pimcore\Aces\Model\ResourceModel\AcesSubmodel\CollectionFactory as AcesSubmodelCollFactory;
use Pimcore\Aces\Model\ResourceModel\AcesYmm\CollectionFactory as AcesYmmCollFactory;
use Pimcore\Ymm\Api\YmmApiManagementInterface;
use \Pimcore\Aces\Api\AcesProductsRepositoryInterface;

class YmmApiManagement implements YmmApiManagementInterface
{

    CONST YEAR_COL = 'year_id';
    CONST MAKE_COL = 'make_name';
    CONST MODEL_COL = 'model_name';
    CONST BASE_VEHICLE_ID = 'base_vehicle_id';
    CONST SUB_MODEL_COL = 'sub_model_name';
    /**
     * @var AcesYmmCollFactory
     */
    private $acesYmmCollFactory;
    /**
     * @var AcesSubmodelCollFactory
     */
    private $acesSubmodelCollFactory;
    /**
     * @var AcesProductsRepositoryInterface
     */
    private $acesProductsRepository;
    /**
     * @var AttributeOptionManagementInterface
     */
    private $attributeOptionManagement;

    private $model_name_options = [];
    private $make_name_options = [];


    /**
     * YmmApiManagement constructor.
     * @param AcesYmmCollFactory              $acesYmmCollFactory
     * @param AcesSubmodelCollFactory         $acesSubmodelCollFactory
     * @param AcesProductsRepositoryInterface $acesProductsRepository
     */
    public function __construct(
        AcesYmmCollFactory $acesYmmCollFactory,
        AcesSubmodelCollFactory $acesSubmodelCollFactory,
        AcesProductsRepositoryInterface $acesProductsRepository,
        AttributeOptionManagementInterface $attributeOptionManagement
    )
    {
        $this->acesYmmCollFactory = $acesYmmCollFactory;
        $this->acesSubmodelCollFactory = $acesSubmodelCollFactory;
        $this->acesProductsRepository = $acesProductsRepository;
        $this->attributeOptionManagement = $attributeOptionManagement;
        $this->fetchModelNameOptions();
        $this->fetchMakeNameOptions();
    }

    protected function getAcesYmmCollection()
    {
        return $this->acesYmmCollFactory->create();
    }

    protected function getAcesProductsCollection()
    {
        return $this->acesProductsRepository->getCollection();
    }

    /**
     * @param $column
     * @return array
     */
    protected function getDistinctColumnValues($column, $collection = null)
    {
        $collection = $collection ? $collection : $this->getAcesYmmCollection();
        $collection->getSelect()
            ->group($column)
            ->order($column)
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([$column]);
        return $collection->getColumnValues($column);

    }

    /**
     * @return array|integer[]
     */
    public function getYearIds()
    {
        $acesProductsYearIds = $this->getAcesYearIds();
        $yearOptions = $this->attributeOptionManagement->getItems(\Magento\Catalog\Model\Product::ENTITY, self::YEAR_COL);
        $yearIds = [];
        if (count($yearOptions)) {
            foreach ($yearOptions as $yearOption) {
                if (!$yearOption->getValue() || !in_array($yearOption->getLabel(), $acesProductsYearIds)) continue;
                $yearIds[$yearOption->getValue()] = $yearOption->getLabel();
            }
        }
        arsort($yearIds);
        return $yearIds;
    }

    /**
     * @return array
     */
    public function getMakeNames()
    {
        $acesProductsMakeNames = $this->getAcesMakeNames();
        $makeNameOptions = $this->attributeOptionManagement->getItems(\Magento\Catalog\Model\Product::ENTITY, self::MAKE_COL);
        $makeNames = [];
        if (count($makeNameOptions)) {
            foreach ($makeNameOptions as $makeNameOption) {
                if (!$makeNameOption->getValue() || !in_array($makeNameOption->getLabel(), $acesProductsMakeNames)) continue;
                $makeNames[$makeNameOption->getValue()] = $makeNameOption->getLabel();
            }
        }
        return $makeNames;
    }

    /**
     * @return array
     */
    public function getModelNames()
    {
        $modelNameOptions = $this->attributeOptionManagement->getItems(\Magento\Catalog\Model\Product::ENTITY, self::MODEL_COL);
        $modelNames = [];
        if (count($modelNameOptions)) {
            foreach ($modelNameOptions as $modelNameOption) {
                if (!$modelNameOption->getValue()) continue;
                $modelNames[$modelNameOption->getValue()] = $modelNameOption->getLabel();
            }
        }
        rsort($modelNames);
        return $modelNames;
    }

    /**
     * @return array
     */
    public function getAcesModelNames()
    {
        return $this->getDistinctColumnValues(self::MODEL_COL);
    }

    /**
     * @return array|integer[]
     */
    public function getAcesYearIds()
    {
        $yearIds = $this->getDistinctColumnValues(self::YEAR_COL, $this->getAcesProductsCollection());
        rsort($yearIds);
        return $yearIds;
    }

    public function getBaseVehicleIds()
    {
        return $this->getAcesYmmCollection()->getAllIds();
    }

    /**
     * @return array
     */
    public function getAcesMakeNames()
    {
        $makeNames = $this->getDistinctColumnValues(self::MAKE_COL, $this->getAcesProductsCollection());
        sort($makeNames);
        return $makeNames;
    }


    /**
     * @param int $year
     * @return string[] Containing Make names
     */
    public function getMakesByYear($year)
    {
        $collection = $this->getAcesProductsCollection(); //$this->getAcesYmmCollection();
        $year ? $collection->addFieldToFilter(self::YEAR_COL, $year) : '';
        $collection->getSelect()
            ->group(self::MAKE_COL)
            ->order(self::MAKE_COL)
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([self::MAKE_COL]);
        $finalData = [];
        if (count($collection)) {
            foreach ($collection as $values) {
                if (!$this->make_name_options[$values[self::MAKE_COL]]) continue;
                $finalData[$this->make_name_options[$values[self::MAKE_COL]]] = $values[self::MAKE_COL];
            }
        }

        $finalData = array_unique($finalData);
        asort($finalData);
        return $finalData;
    }

    /**
     * @param int $year
     * @return string[] Containing Make names
     */
    public function getMakeByYear($year, $inJson = true)
    {
        $collection = $this->getAcesProductsCollection(); //$this->getAcesYmmCollection();
        $year ? $collection->addFieldToFilter(self::YEAR_COL, $year) : '';
        $collection->getSelect()
            ->group(self::MAKE_COL)
            ->order(self::MAKE_COL)
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([self::MAKE_COL]);
        $finalData = [];
        if (count($collection)) {
            foreach ($collection as $values) {
                if (!$this->make_name_options[$values[self::MAKE_COL]]) continue;
                $finalData[$this->make_name_options[$values[self::MAKE_COL]]] = $values[self::MAKE_COL];
            }
        }

        $finalData = array_unique($finalData);
        asort($finalData);
        return json_encode($finalData);
    }

    /**
     * @param int $year
     * @return string[] Containing Make names
     */
    public function getAcesMakeByYear($year)
    {
        $acesYmmCollection = $this->getAcesYmmCollection();
        $acesYmmCollection->addFieldToFilter(self::YEAR_COL, $year);
        $acesYmmCollection->getSelect()
            ->group(self::MAKE_COL)
            ->order(self::MAKE_COL)
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([self::MAKE_COL]);
        return $acesYmmCollection->getColumnValues(self::MAKE_COL);
    }


    /**
     * @param int    $year
     * @param string $make
     * @return string[]
     */
    public function getModelByYearAndMake($year, $make)
    {
        $collection = $this->getAcesProductsCollection(); //$this->getAcesYmmCollection();
        $year ? $collection->addFieldToFilter(self::YEAR_COL, $year) : '';
        $make ? $collection->addFieldToFilter(self::MAKE_COL, $make) : '';
        $collection->getSelect()
            ->group(self::MODEL_COL)
            ->order(self::MODEL_COL)
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([self::MODEL_COL]);
        $finalData = [];
        if (count($collection)) {
            foreach ($collection as $values) {
                if (!$this->model_name_options[$values[self::MODEL_COL]]) continue;
                $finalData[$this->model_name_options[$values[self::MODEL_COL]]] = $values[self::MODEL_COL];
            }
        }

        $finalData = array_unique($finalData);
        asort($finalData);
        return json_encode($finalData);
    }

    /**
     * @param int $baseid
     * @return string[]
     */
    function getSubmodelByBaseId($baseid)
    {
        /** @var  $acesSubmoelCollection \Pimcore\Aces\Model\ResourceModel\AcesSubmodel\Collection */
        $acesSubmoelCollection = $this->acesSubmodelCollFactory->create();
        $acesSubmoelCollection->addFieldToFilter(self::BASE_VEHICLE_ID, $baseid);
        $acesSubmoelCollection->getSelect()
            ->group(self::SUB_MODEL_COL)
            ->order(self::SUB_MODEL_COL)
            ->reset(\Zend_Db_Select::COLUMNS)
            ->columns([self::SUB_MODEL_COL]);
        return $acesSubmoelCollection->getColumnValues(self::SUB_MODEL_COL);
    }

    private function fetchModelNameOptions()
    {
        $modelNames = $this->attributeOptionManagement->getItems(\Magento\Catalog\Model\Product::ENTITY, self::MODEL_COL);
        foreach ($modelNames as $modelName) {
            if ($modelName->getValue()) {
                $this->model_name_options[$modelName->getLabel()] = $modelName->getValue();
            }
        }
    }

    private function fetchMakeNameOptions()
    {
        $makeNames = $this->attributeOptionManagement->getItems(\Magento\Catalog\Model\Product::ENTITY, self::MAKE_COL);
        foreach ($makeNames as $makeName) {
            if ($makeName->getValue()) {
                $this->make_name_options[$makeName->getLabel()] = $makeName->getValue();
            }
        }
    }

}