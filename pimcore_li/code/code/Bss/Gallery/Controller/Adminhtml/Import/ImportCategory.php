<?php
namespace Bss\Gallery\Controller\Adminhtml\Import;

class ImportCategory extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Bss\ReviewsImport\Model\ResourceModel\Import
     */
    protected $importModel;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @var \Magento\Framework\Filesystem\Directory\WriteInterface
     */
    protected $varDirectory;

    /**
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $fileUploaderFactory;

    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    /**
     * @var \Magento\Framework\File\Size
     */
    protected $fileSize;

    /**
     * ImportCategory constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Bss\Gallery\Model\ResourceModel\CategoryImport $importModel
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\File\Size $fileSize
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Bss\Gallery\Model\ResourceModel\CategoryImport $importModel,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\File\Size $fileSize
    ) {
        parent::__construct($context);
        $this->importModel = $importModel;
        $this->request=$request;
        $this->filesystem = $filesystem;
        $this->fileUploaderFactory = $fileUploaderFactory;
        $this->fileSize = $fileSize;
    }

    /**
     * @return $this|\Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        $this->varDirectory = $this->filesystem
            ->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR);
        $filepath = "import/image-category/".$this->request->getFiles('file-category')['name'];
        $size = $this->request->getFiles('file-category')['size'];

        if (($size==0) || ($size>$this->fileSize->getMaxFileSize())) {
            $this->messageManager->addError($this->getMaxUploadSizeMessage());
            $this->resultRedirectFactory->create()->setPath(
                '*/*/index',
                ['_secure'=>$this->getRequest()->isSecure()]
            );
        }
        try {
            $target = $this->varDirectory->getAbsolutePath('import/image-category');
            $uploader = $this->fileUploaderFactory->create(['fileId' => 'file-category']);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(false);
            $result = $uploader->save($target);
            if ($result['file']) {
                $this->messageManager->addSuccess(__('File has been successfully uploaded in var/import/image-category'));
            }
            $this->importModel->setFilePath($filepath);
            $this->importModel->importFromCsvFile($this->request->getFiles('file-category'));
            $this->messageManager->addSuccess(__('Inserted Row(s): '. $this->importModel->getInsertedRows()));
            if ($this->importModel->getInvalidRows()>0) {
                $this->messageManager->addError(__(
                    "Invalid Row(s): %1: <br/> Empty Title in Row(s): %2",
                    $this->importModel->getInvalidRows(),
                    $this->importModel->getWrongTitleRows())
                );
//                $this->messageManager->addError(__('Empty Title in Row(s): ' . $this->importModel->getWrongTitleRows()));
            }
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
        }
        return $this->resultRedirectFactory->create()->setPath(
            '*/*/index',
            ['_secure'=>$this->getRequest()->isSecure()]
        );
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    protected function getMaxUploadSizeMessage()
    {
        $maxImageSize = $this->fileSize->getMaxFileSizeInMb();
        if ($maxImageSize) {
            $message = __('Make sure your file isn\'t more than %1M.', $maxImageSize);
        } else {
            $message = __('We can\'t provide the upload settings right now.');
        }
        return $message;
    }
}