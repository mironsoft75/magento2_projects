<?php
namespace Bss\Gallery\Controller\Adminhtml\Export;

use Magento\Framework\App\Action\Context;

class ExportItem extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Bss\Gallery\Model\ResourceModel\Export|\Bss\ReviewsImport\Model\ResourceModel\Export
     */
    protected $export;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $varDirectory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $io;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $datetime;

    /**
     * @var \Magento\Framework\File\Csv
     */
    protected $csv;

    /**
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $fileFactory;

    /**
     * @var \Magento\Framework\Controller\Result\RawFactory
     */
    protected $resultRawFactory;


    /**
     * ExportCategory constructor.
     * @param Context $context
     * @param \Bss\Gallery\Model\ResourceModel\Export $export
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $datetime
     * @param \Magento\Framework\Filesystem\Io\File $io
     * @param \Magento\Framework\File\Csv $csv
     * @param \Magento\Framework\App\Response\Http\FileFactory $fileFactory
     * @param \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
     */
    public function __construct(
        Context $context,
        \Bss\Gallery\Model\ResourceModel\Export $export,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Stdlib\DateTime\DateTime $datetime,
        \Magento\Framework\Filesystem\Io\File $io,
        \Magento\Framework\File\Csv $csv,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\Controller\Result\RawFactory $resultRawFactory
    ) {
        $this->export=$export;
        $this->filesystem = $filesystem;
        $this->datetime = $datetime;
        $this->io=$io;
        $this->csv=$csv;
        $this->fileFactory=$fileFactory;
        $this->resultRawFactory=$resultRawFactory;
        parent::__construct($context);
    }

    /**
     * @return $this|\Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {

        $this->varDirectory = $this->filesystem
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);

        $dir=$this->varDirectory->getAbsolutePath('export/item');
        $this->io->mkdir($dir, 0775);

        if ($this->getRequest()->getParam('export_file_type') == "CSV") {
            $currentDate = $this->export->formatDate($this->datetime->date());
            $outputFile = $dir."/Item_" . $currentDate . ".csv";
            $fileName = "Item_" . $currentDate . ".csv";
            $albums = $this->export->getItemTable();
            $data = $this->export->getExportItems($albums);
            try {
                $this->csv->saveData($outputFile, $data);
                $this->fileFactory->create(
                    $fileName,
                    [
                        'type'  => "filename",
                        'value' => "export/item/".$fileName,
                        'rm'    => true,
                    ],
                    \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                    'text/csv',
                    null
                );
                $resultRaw = $this->resultRawFactory->create();
                return $resultRaw;
            } catch (\Exception $e) {
                $this->messageManager->addError($e->getMessage());
            }
        }
        return $this->resultRedirectFactory->create()->setPath(
            '*/*/index',
            ['_secure'=>$this->getRequest()->isSecure()]
        );
    }
}
