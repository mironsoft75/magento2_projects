<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/11/18
 * Time: 10:54 AM
 */

namespace Codilar\StoneAndMetalRates\Model\Import;

use Magento\Store\Model\StoreManagerInterface;
use Codilar\StoneAndMetalRates\Model\Import\StoneAndMetalRates\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Store\Model\StoreRepository;

/**
 * Class StoneAndMetalRates
 * @package Codilar\StoneAndMetalRates\Model\Import
 */
class StoneAndMetalRates extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const NAME = 'name';
    const RATE = 'rate';
    const STORE_ID = 'store_id';
    const STATUS = 'status';
    const TYPE='type';
    const UNIT='unit';
    const STONE_NAME_FOR_CUSTOMER='stone_name_for_customer';
    const STONE_SHAPE='stone_shape';
    const STONE_QUALITY='stone_quality';
    const METAL_TYPE='metal_type';
    const METAL_PURITY='metal_purity';
    const TABLE_Entity = 'stone_metal_rates';
    /**
     * @var StoreRepository
     */
    protected $_storeRepository;
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
        ValidatorInterface::ERROR_NAME_IS_EMPTY => 'Name is empty',
        ValidatorInterface::ERROR_RATE_IS_EMPTY => 'Rate is empty',
        ValidatorInterface::ERROR_TYPE_IS_EMPTY => 'Entity type is empty',

    ];

    /**
     * @var array
     */
    protected $_permanentAttributes = [ self::NAME, self::RATE, self::TYPE];
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = false;
    /**
     * @var \Magento\Customer\Model\GroupFactory
     */
    protected $groupFactory;
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
        self::NAME,
        self::RATE,
        self::STORE_ID,
        self::STATUS,
        self::TYPE,
        self::STONE_NAME_FOR_CUSTOMER,
        self::STONE_SHAPE,
        self::STONE_QUALITY,
        self::METAL_TYPE,
        self::METAL_PURITY
    ];

    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;

    /**
     * @var array
     */
    protected $_validators = [];

    /**
     * @var
     */
    protected $_connection;
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resource;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    private $allStoreIds;


    public function __construct(
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\ImportExport\Helper\Data $importExportData,
        \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Stdlib\StringUtils $string,
        ProcessingErrorAggregatorInterface $errorAggregator,
        \Magento\Customer\Model\GroupFactory $groupFactory,
        StoreRepository $storeRepository,
        StoreManagerInterface $storeManager
    )
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_resource = $resource;
        $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
        $this->groupFactory = $groupFactory;
        $this->_storeRepository = $storeRepository;
        $this->storeManager = $storeManager;
    }

    /**
     * @return array
     */
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }

    /**
     * @return string
     */
    public function getEntityTypeCode()
    {
        return 'metal_stone_rates';
    }

    /**
     * @return bool
     */
    protected function _importData()
    {
        if (in_array($this->getBehavior(), [
            \Magento\ImportExport\Model\Import::BEHAVIOR_ADD_UPDATE,
            \Magento\ImportExport\Model\Import::BEHAVIOR_APPEND
        ])) {
            $this->saveEntity();
        }
        return true;
    }


    public function validateRow(array $rowData, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        if (!isset($rowData[self::NAME]) || empty($rowData[self::NAME])) {
            $this->addRowError(ValidatorInterface::ERROR_NAME_IS_EMPTY, $rowNum);
            return false;
        }
        if (!isset($rowData[self::RATE]) || empty($rowData[self::RATE])) {
            $this->addRowError(ValidatorInterface::ERROR_RATE_IS_EMPTY, $rowNum);
            return false;
        }


        if (!isset($rowData[self::TYPE]) || empty($rowData[self::TYPE])) {
            $this->addRowError(ValidatorInterface::ERROR_TYPE_IS_EMPTY, $rowNum);
            return false;
        }


        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * @return $this
     */
    protected function saveAndReplaceEntity()
    {
        try {
            $behavior = $this->getBehavior();
            while ($bunch = $this->_dataSourceModel->getNextBunch()) {
                $entityList = [];
                foreach ($bunch as $rowNum => $rowData) {
                    if ($this->getErrorAggregator()->hasToBeTerminated()) {
                        $this->getErrorAggregator()->addRowToSkip($rowNum);
                        continue;
                    }
                    if($rowData[self::TYPE]=='metal')
                    {
                        $entityList[] = [
                            self::NAME => $rowData[self::NAME],
                            self::RATE => $rowData[self::RATE],
                            self::STORE_ID => 1,
                            self::TYPE => $rowData[self::TYPE],
                            self::STATUS =>1,
                            self::METAL_TYPE =>$rowData[self::METAL_TYPE],
                            self::METAL_PURITY =>$rowData[self::METAL_PURITY],
                            self::UNIT => 'gram',
                        ];
                    }
                    if($rowData[self::TYPE]=='stone')
                    {
                        $entityList[] = [
                            self::NAME => $rowData[self::NAME],
                            self::RATE => $rowData[self::RATE],
                            self::STORE_ID => 1,
                            self::TYPE => $rowData[self::TYPE],
                            self::STATUS => 1,
                            self::STONE_NAME_FOR_CUSTOMER => $rowData[self::STONE_NAME_FOR_CUSTOMER],
                            self::STONE_SHAPE => $rowData[self::STONE_SHAPE],
                            self::STONE_QUALITY => $rowData[self::STONE_QUALITY],
                            self::UNIT => 'carat',
                        ];
                    }
                }

                if (in_array($behavior, [
                    \Magento\ImportExport\Model\Import::BEHAVIOR_ADD_UPDATE,
                    \Magento\ImportExport\Model\Import::BEHAVIOR_APPEND
                ])) {
                    $this->saveEntityFinish($entityList, self::TABLE_Entity);
                }
            }
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
        return $this;
    }

    /**
     * @param array $entityData
     * @param $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $this->_connection->insertOnDuplicate($tableName, $entityData,
                [
                    self::NAME,
                    self::RATE,
                    self::STORE_ID,
                    self::TYPE,
                    self::STATUS,
                    self::UNIT,
                    self::STONE_NAME_FOR_CUSTOMER,
                    self::STONE_SHAPE,
                    self::STONE_QUALITY,
                    self::METAL_TYPE,
                    self::METAL_PURITY
                ]);
        }
        return $this;
    }

    /**
     * @return $this
     */
    public function saveEntity()
    {
        $this->saveAndReplaceEntity();
        return $this;
    }


//    private function getAllStoreIds()
//    {
//        if (!$this->allStoreIds) {
//            $this->allStoreIds = array_keys($this->storeManager->getStores(true, false));
//        }
//        return $this->allStoreIds;
//
//    }
//
//    private function getValidStore($storeId)
//    {
//        $allStoreIds = $this->getAllStoreIds();
//        if (!in_array($storeId, $allStoreIds)) {
//            $storeId = 0;
//        }
//        return $storeId;
//    }
}