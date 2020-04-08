<?php
namespace Bss\Gallery\Model\ResourceModel;

use Magento\Framework\App\Filesystem\DirectoryList;

class CategoryImport
{
    protected $insertedRows = 0;

    protected $wrongTitleRows = "";

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
        if ($rowData['Album Title'] == "") {
            $this->wrongTitleRows.= "$rowNum, ";
            return false;
        }
    }

    protected function processData($data) {
        $importData = $this->prepareData($data);

        if ($this->checkExistedCategoryId($data['Album Id']) === false) {
            $this->writeAdapter->insert($this->getTableName('bss_gallery_category'), $importData);

            $updateData['url_key'] = $this->getUrlKey($importData['title']);
            $condition = ["{$this->getTableName('bss_gallery_category')}.category_id = ?" => $this->getLastCategoryId()];
            $this->writeAdapter->update($this->getTableName('bss_gallery_category'), $updateData, $condition);
        } else {
            unset($importData['create_time']);

            $condition = ["{$this->getTableName('bss_gallery_category')}.category_id = ?" => $this->checkExistedCategoryId($data['Album Id'])];
            $this->writeAdapter->update($this->getTableName('bss_gallery_category'), $importData, $condition);
        }
    }

    protected function prepareData($data)
    {
        $importData = [];
        $importData['title'] = $data['Album Title'];
        $importData['category_description'] = $data['Album Description'];
        $importData['category_meta_keywords'] = $data['Meta Key'];
        $importData['category_meta_description'] = $data['Meta Description'];

        if ($data['Layout'] === '1' || $data['Layout'] === '2') {
            $importData['item_layout'] = $data['Layout'];
        } else {
            $importData['item_layout'] = 1;
        }

        if ($data['Auto Play'] === '0' || $data['Auto Play'] === '1') {
            $importData['slider_auto_play'] = $data['Auto Play'];
        } else {
            $importData['slider_auto_play'] = 1;
        }

        if ($data['Status'] === '0' || $data['Status'] === '1') {
            $importData['is_active'] = $data['Status'];
        } else {
            $importData['is_active'] = 1;
        }

        $importData['Item_ids'] = $data['Item Ids'];
        $importData['create_time'] = date('Y-m-d H:i:s');
        $importData['update_time'] = date('Y-m-d H:i:s');
        return $importData;
    }

    protected function getUrlKey($title)
    {
        $urlKey = str_replace(" ", "-", strtolower($title));

        if ($this->checkExistedUrlKey($urlKey) !== false) {
            $urlKey = $urlKey . "-" . $this->getLastCategoryId();
        }

        return $urlKey;
    }

    protected function checkExistedUrlKey($urlKey)
    {
        $select = $this->readAdapter->select()
            ->from(
                $this->getTableName('bss_gallery_category'),
                [
                    'category_id'
                ]
            )
            ->where('url_key = :url_key');
        $bind = [
            ':url_key' => $urlKey
        ];
        $categoryId = $this->readAdapter->fetchOne($select, $bind);
        return $categoryId;
    }

    protected function checkExistedCategoryId($categoryId)
    {
        $select = $this->readAdapter->select()
            ->from(
                $this->getTableName('bss_gallery_category'),
                [
                    'category_id'
                ]
            )
            ->where('category_id = :category_id');
        $bind = [
            ':category_id' => $categoryId
        ];
        $categoryId = $this->readAdapter->fetchOne($select, $bind);
        return $categoryId;
    }

    protected function getLastCategoryId()
    {
        $select = $this->readAdapter->select()
            ->from(
                [$this->getTableName('bss_gallery_category')],
                ['category_id']
            )
            ->order('category_id DESC')
            ->limit(1);
        $maxId = $this->readAdapter->fetchOne($select);
        return $maxId;
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
        return rtrim($this->wrongTitleRows, ",");
    }

    /**
     * @return int
     */
    public function getInvalidRows()
    {
        return $this->invalidDataRows;
    }
}
