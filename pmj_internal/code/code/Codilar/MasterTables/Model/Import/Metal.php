<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/5/19
 * Time: 1:21 PM
 */

namespace Codilar\MasterTables\Model\Import;

use Magento\Framework\App\ObjectManager;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\ResourceConnection;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Codilar\MasterTables\Model\Import\Metal\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\ImportExport\Helper\Data as ImportExportData;
use Magento\ImportExport\Model\ResourceModel\Import\Data as ImportData;
use Magento\ImportExport\Model\ResourceModel\Helper;

/**
 * Class Metal
 * @package Codilar\MasterTables\Model\Import
 */
class Metal extends AbstractEntity
{
    const KARAT_COLOR = 'karat_color';
    const METAL_TYPE = 'metal_type';
    const KARAT = 'karat';
    const METAL_COLOR = 'metal_color';
    const TABLE_ENTITY = 'codilar_metal';
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
        ValidatorInterface::ERROR_KARAT_COLOR_IS_EMPTY => 'Karat Color is empty',
        ValidatorInterface::ERROR_METAL_TYPE_IS_EMPTY => 'Metal Type is empty',
        ValidatorInterface::ERROR_KARAT_IS_EMPTY => 'Karat field is empty',
        ValidatorInterface::ERROR_METAL_COLOR_IS_EMPTY => 'Metal Color is empty',

    ];
    private $serializer;

    /**
     * @var array
     */
    protected $_permanentAttributes = [self::KARAT_COLOR, self::METAL_TYPE, self::KARAT, self::METAL_COLOR];
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
        self::KARAT_COLOR,
        self::METAL_TYPE,
        self::KARAT,
        self::METAL_COLOR
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
        return 'codilar_metal';
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
        if (!isset($rowData[self::KARAT_COLOR]) || empty($rowData[self::KARAT_COLOR])) {
            $this->addRowError(ValidatorInterface::ERROR_KARAT_COLOR_IS_EMPTY, $rowNum);
            return false;
        }
        if (!isset($rowData[self::METAL_TYPE]) || empty($rowData[self::METAL_TYPE])) {
            $this->addRowError(ValidatorInterface::ERROR_METAL_TYPE_IS_EMPTY, $rowNum);
            return false;
        }


        if (!isset($rowData[self::KARAT]) || empty($rowData[self::KARAT])) {
            $this->addRowError(ValidatorInterface::ERROR_KARAT_IS_EMPTY, $rowNum);
            return false;
        }
        if (!isset($rowData[self::METAL_COLOR]) || empty($rowData[self::METAL_COLOR])) {
            $this->addRowError(ValidatorInterface::ERROR_METAL_COLOR_IS_EMPTY, $rowNum);
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
                        self::KARAT_COLOR => $rowData[self::KARAT_COLOR],
                        self::KARAT => $rowData[self::KARAT],
                        self::METAL_TYPE => $rowData[self::METAL_TYPE],
                        self::METAL_COLOR => $rowData[self::METAL_COLOR],
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
                    self::KARAT_COLOR,
                    self::METAL_TYPE,
                    self::KARAT,
                    self::METAL_COLOR
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