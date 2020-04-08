<?php
namespace Bss\Gallery\Model\ResourceModel;

use Magento\Framework\App\Filesystem\DirectoryList;

class ItemImport
{
    protected $insertedRows = 0;

    protected $wrongTitleRows = "";

    protected $wrongDescriptionRows = "";

    protected $invalidDataRows = 0;

    /**
     * @var array
     */
    protected $tableNames = [];

    /**
     * @var \Magento\ImportExport\Model\Import\Source\CsvFactory
     */
    protected $sourceCsvFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $readAdapter;

    /**
     * @var \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected $writeAdapter;

    protected $resourceConnection;

    protected $storeManager;

    /**
     * Import constructor.
     * @param \Magento\ImportExport\Model\Import\Source\CsvFactory $sourceCsvFactory
     * @param \Magento\Framework\Filesystem $filesystem
     */
    public function __construct(
        \Magento\ImportExport\Model\Import\Source\CsvFactory $sourceCsvFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->sourceCsvFactory = $sourceCsvFactory;
        $this->filesystem = $filesystem;
        $this->resourceConnection = $resourceConnection;
        $this->storeManager = $storeManager;

        $this->readAdapter = $this->resourceConnection->getConnection('core_read');
        $this->writeAdapter = $this->resourceConnection->getConnection('core_write');
    }

    /**
     * @param int $entity
     * @return bool|string
     */
    protected function getTableName($entity)
    {
        if (!isset($this->tableNames[$entity])) {
            try {
                $this->tableNames[$entity] = $this->resourceConnection->getTableName($entity);
            } catch (\Exception $e) {
                return false;
            }
        }
        return $this->tableNames[$entity];
    }

    /**
     * @param $file
     * @return void
     */
    public function importFromCsvFile($file)
    {
        $sourceCsv = $this->createSourceCsvModel($this->getFilePath());
        $sourceCsv->rewind();
        $numRow = 0;

        while ($sourceCsv->valid()) {
            $numRow++;
            $data = $sourceCsv->current();

            if ($this->validation($numRow, $data) === false) {
                $this->invalidDataRows++;
            } else {
                $this->processData($data);
                $this->insertedRows++;
            }

            $sourceCsv->next();
        }
    }

    protected function validation($rowNum, $rowData)
    {
        if ($rowData['Item Name'] == "") {
            $this->wrongTitleRows .= "$rowNum, ";
            return false;
        }

        if ($rowData['Item Description'] == "") {
            $this->wrongDescriptionRows .= "$rowNum, ";
            return false;
        }
    }

    protected function processData($data) {
        $importData = $this->prepareData($data);

        if ($this->checkExistedItemId($data['Item Id']) === false) {
            $this->writeAdapter->insert($this->getTableName('bss_gallery_item'), $importData);
            $itemId = $this->getLastItemId();
        } else {
            unset($importData['create_time']);

            $condition = ["{$this->getTableName('bss_gallery_item')}.item_id = ?" => $this->checkExistedItemId($data['Item Id'])];
            $this->writeAdapter->update($this->getTableName('bss_gallery_item'), $importData, $condition);
            $itemId = $data['Item Id'];
        }

        $categoryIds = explode(",", $importData['category_ids']);
        foreach ($categoryIds as $categoryId) {
            $itemIds = explode(",", $this->getItemsFromCategory($categoryId));
            if (!in_array($itemId, $itemIds)) {
                $updateData['item_ids'] = $this->getItemsFromCategory($categoryId) . "," . $itemId;

                $condition = ["{$this->getTableName('bss_gallery_category')}.category_id = ?" => $categoryId];
                $this->writeAdapter->update($this->getTableName('bss_gallery_category'), $updateData, $condition);
            }
        }
    }

    protected function prepareData($data)
    {
        $importData = [];
        $importData['title'] = $data['Item Name'];
        $importData['description'] = $data['Item Description'];
        $importData['image'] = $data['Image Path'];
        $importData['video'] = $data['Video Url'];
        $importData['sorting'] = $data['Sort Order'];

        if ($data['Status'] === '0' || $data['Status'] === '1') {
            $importData['is_active'] = $data['Status'];
        } else {
            $importData['is_active'] = 1;
        }

        $importData['category_ids'] = $data['Category Ids'];
        $importData['create_time'] = date('Y-m-d H:i:s');
        $importData['update_time'] = date('Y-m-d H:i:s');
        return $importData;
    }

    protected function checkExistedItemId($itemId)
    {
        $select = $this->readAdapter->select()
            ->from(
                $this->getTableName('bss_gallery_item'),
                [
                    'item_id'
                ]
            )
            ->where('item_id = :item_id');
        $bind = [
            ':item_id' => $itemId
        ];
        $itemId = $this->readAdapter->fetchOne($select, $bind);
        return $itemId;
    }

    protected function getLastItemId()
    {
        $select = $this->readAdapter->select()
            ->from(
                [$this->getTableName('bss_gallery_item')],
                ['item_id']
            )
            ->order('item_id DESC')
            ->limit(1);
        $maxId = $this->readAdapter->fetchOne($select);
        return $maxId;
    }

    protected function getItemsFromCategory($categoryId)
    {
        $select = $this->readAdapter->select()
            ->from(
                $this->getTableName('bss_gallery_category'),
                [
                    'item_ids'
                ]
            )
            ->where('category_id = :category_id');
        $bind = [
            ':category_id' => $categoryId
        ];
        $itemIds = $this->readAdapter->fetchOne($select, $bind);
        return $itemIds;
    }

    /**
     * @param string $sourceFile
     * @return \Magento\ImportExport\Model\Import\Source\Csv
     */
    protected function createSourceCsvModel($sourceFile)
    {
        return $this->sourceCsvFactory->create(
            [
                'file' => $sourceFile,
                'directory' => $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR)
            ]
        );
    }

    /**
     * @param string $filePath
     * @return $this
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * @return int
     */
    public function getInsertedRows()
    {
        return $this->insertedRows;
    }

    /**
     * @return int
     */
    public function getWrongTitleRows()
    {
        return $this->wrongTitleRows;
    }

    public function getWrongDescriptionRows()
    {
        return $this->wrongDescriptionRows;
    }

    /**
     * @return int
     */
    public function getInvalidRows()
    {
        return $this->invalidDataRows;
    }
}
