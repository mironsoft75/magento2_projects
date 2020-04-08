<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/5/19
 * Time: 3:37 PM
 */

namespace Codilar\MasterTables\Model\Import;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ResourceConnection;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Codilar\MasterTables\Model\Import\StoneBom\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\ImportExport\Helper\Data as ImportExportData;
use Magento\ImportExport\Model\ResourceModel\Import\Data as ImportData;
use Magento\ImportExport\Model\ResourceModel\Helper;

class StoneBom extends AbstractEntity
{
    const STONE_BOM_VARIANT = 'stone_bom_variant';
    const STONE_TYPE = 'stone_type';
    const STONE_QUALITY = 'stone_quality';
    const STONE_COLOR = 'stone_color';
    const STONE_SHAPE = 'stone_shape';
    const WT_RANGE = 'wt_range';
    const INDIAN_RATE_CARAT = 'indian_rate_carat';
    const USA_RATE_CARAT = 'usa_rate_carat';

    const TABLE_ENTITY = 'codilar_stone_bom';
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
        ValidatorInterface::ERROR_STONE_BOM_VARIANT_IS_EMPTY => 'Stone Bom Variant  is empty',
        ValidatorInterface::ERROR_STONE_TYPE_IS_EMPTY => 'Stone Type is empty',
        ValidatorInterface::ERROR_INDIAN_RATE_CARAT_IS_EMPTY => 'Indian Rate is empty',
        ValidatorInterface::ERROR_USA_RATE_CARAT_IS_EMPTY => 'USA Rate is empty',


    ];
    private $serializer;

    /**
     * @var array
     */
    protected $_permanentAttributes = [self::STONE_BOM_VARIANT, self::STONE_TYPE, self::INDIAN_RATE_CARAT, self::USA_RATE_CARAT];
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
        self::STONE_BOM_VARIANT,
        self::STONE_TYPE,
        self::INDIAN_RATE_CARAT,
        self::USA_RATE_CARAT,
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
     * LocationName constructor.
     * @param ImportExportData $importExportData
     * @param ImportData $importData
     * @param ResourceConnection $resource
     * @param Helper $resourceHelper
     * @param JsonHelper $jsonHelper
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     */
    public function __construct(
        ImportExportData $importExportData,
        ImportData $importData,
        ResourceConnection $resource,
        Helper $resourceHelper,
        JsonHelper $jsonHelper,
        ProcessingErrorAggregatorInterface $errorAggregator
    )
    {
        $this->jsonHelper = $jsonHelper;
        $this->_importExportData = $importExportData;
        $this->_resourceHelper = $resourceHelper;
        $this->_dataSourceModel = $importData;
        $this->_connection = $resource->getConnection(ResourceConnection::DEFAULT_CONNECTION);
        $this->errorAggregator = $errorAggregator;
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
        return 'codilar_stone_bom';
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

    /**
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
        if (isset($this->_validatedRows[$rowNum])) {
            return !$this->getErrorAggregator()->isRowInvalid($rowNum);
        }
        $this->_validatedRows[$rowNum] = true;
        if (!isset($rowData[self::STONE_BOM_VARIANT]) || empty($rowData[self::STONE_BOM_VARIANT])) {
            $this->addRowError(ValidatorInterface::ERROR_STONE_BOM_VARIANT_IS_EMPTY, $rowNum);
            return false;
        }
        if (!isset($rowData[self::STONE_TYPE]) || empty($rowData[self::STONE_TYPE])) {
            $this->addRowError(ValidatorInterface::ERROR_STONE_TYPE_IS_EMPTY, $rowNum);
            return false;
        }


        if (!isset($rowData[self::INDIAN_RATE_CARAT])) {
            $this->addRowError(ValidatorInterface::ERROR_INDIAN_RATE_CARAT_IS_EMPTY, $rowNum);
            return false;
        }
        if (!isset($rowData[self::USA_RATE_CARAT])) {
            $this->addRowError(ValidatorInterface::ERROR_USA_RATE_CARAT_IS_EMPTY, $rowNum);
            return false;
        }


        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }

    /**
     * @return Json|mixed
     */
    public function getSerializer()
    {
        if (null === $this->serializer) {
            $this->serializer = ObjectManager::getInstance()->get(Json::class);
        }
        return $this->serializer;
    }

    /**
     * @return $this|AbstractEntity
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\ValidatorException
     */
    protected function _saveValidatedBunches()
    {
        $source = $this->_getSource();
        $currentDataSize = 0;
        $bunchRows = [];
        $startNewBunch = false;
        $nextRowBackup = [];
        $maxDataSize = $this->_resourceHelper->getMaxDataSize();
        $bunchSize = $this->_importExportData->getBunchSize();
        $skuSet = [];

        $source->rewind();
        $this->_dataSourceModel->cleanBunches();

        while ($source->valid() || $bunchRows) {
            if ($startNewBunch || !$source->valid()) {
                $this->_dataSourceModel->saveBunch($this->getEntityTypeCode(), $this->getBehavior(), $bunchRows);
                $bunchRows = $nextRowBackup;
                $currentDataSize = strlen($this->getSerializer()->serialize($bunchRows));
                $startNewBunch = false;
                $nextRowBackup = [];
            }
            if ($source->valid()) {
                try {
                    $rowData = $source->current();
                } catch (\InvalidArgumentException $e) {
                    $this->addRowError($e->getMessage(), $this->_processedRowsCount);
                    $this->_processedRowsCount++;
                    $source->next();
                    continue;
                }

                $this->_processedRowsCount++;

                if ($this->validateRow($rowData, $source->key())) {
                    // add row to bunch for save
                    $rowData = $this->_prepareRowForDb($rowData);
                    $rowSize = strlen($this->jsonHelper->jsonEncode($rowData));

                    $isBunchSizeExceeded = $bunchSize > 0 && count($bunchRows) >= $bunchSize;

                    if ($currentDataSize + $rowSize >= $maxDataSize || $isBunchSizeExceeded) {
                        $startNewBunch = true;
                        $nextRowBackup = [$source->key() => $rowData];
                    } else {
                        $bunchRows[$source->key()] = $rowData;
                        $currentDataSize += $rowSize;
                    }
                }
                $source->next();
            }
        }
        $this->_processedEntitiesCount = count($skuSet);

        return $this;
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
                    $entityList[] = [
                        self::STONE_BOM_VARIANT => $rowData[self::STONE_BOM_VARIANT],
                        self::STONE_TYPE => $rowData[self::STONE_TYPE],
                        self::STONE_QUALITY => $rowData[self::STONE_QUALITY],
                        self::STONE_COLOR => $rowData[self::STONE_COLOR],
                        self::STONE_SHAPE => $rowData[self::STONE_SHAPE],
                        self::WT_RANGE => $rowData[self::WT_RANGE],
                        self::INDIAN_RATE_CARAT => $rowData[self::INDIAN_RATE_CARAT],
                        self::USA_RATE_CARAT => $rowData[self::USA_RATE_CARAT],
                    ];


                }
                if (in_array($behavior, [
                    \Magento\ImportExport\Model\Import::BEHAVIOR_ADD_UPDATE,
                    \Magento\ImportExport\Model\Import::BEHAVIOR_APPEND
                ])) {
                    $this->saveEntityFinish($entityList, self::TABLE_ENTITY);
                }
            }
        } catch
        (\Exception $e) {
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
            $this->countItemsCreated = $this->countItemsCreated + count($entityData);
            $tableName = $this->_connection->getTableName($table);
            $this->_connection->insertOnDuplicate($tableName, $entityData,
                [
                    self::STONE_BOM_VARIANT,
                    self::STONE_TYPE,
                    self::STONE_QUALITY,
                    self::STONE_COLOR,
                    self::STONE_SHAPE,
                    self::WT_RANGE,
                    self::INDIAN_RATE_CARAT,
                    self::USA_RATE_CARAT,
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
}