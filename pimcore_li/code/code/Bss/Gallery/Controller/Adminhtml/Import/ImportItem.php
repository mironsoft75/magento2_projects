<?php
namespace Bss\Gallery\Controller\Adminhtml\Import;

class ImportItem extends \Magento\Framework\App\Action\Action
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
     * ImportItem constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Bss\Gallery\Model\ResourceModel\ItemImport $importModel
     * @param \Magento\Framework\App\Request\Http $request
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \Magento\Framework\File\Size $fileSize
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Bss\Gallery\Model\ResourceModel\ItemImport $importModel,
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
        $filepath = "import/image-item/".$this->request->getFiles('file-item')['name'];
        $size = $this->request->getFiles('file-item')['size'];

        if (($size==0) || ($size>$this->fileSize->getMaxFileSize())) {
            $this->messageManager->addError($this->getMaxUploadSizeMessage());
            $this->resultRedirectFactory->create()->setPath(
                '*/*/index',
                ['_secure'=>$this->getRequest()->isSecure()]
            );
        }
        try {
            $target = $this->varDirectory->getAbsolutePath('import/image-item');
            $uploader = $this->fileUploaderFactory->create(['fileId' => 'file-item']);
            $uploader->setAllowedExtensions(['csv']);
            $uploader->setAllowRenameFiles(false);
            $result = $uploader->save($target);
            if ($result['file']) {
                $this->messageManager->addSuccess(__('File has been successfully uploaded in var/import/image-item'));
            }
            $this->importModel->setFilePath($filepath);
            $this->importModel->importFromCsvFile($this->request->getFiles('file-item'));
            $this->messageManager->addSuccess(__('Inserted Row(s): '. $this->importModel->getInsertedRows()));

            if ($this->importModel->getInvalidRows()>0) {
                $errorMessage = 'Invalid Row(s): ' . $this->importModel->getInvalidRows() . " :";

                if ($this->importModel->getWrongTitleRows() != "") {
                    $errorMessage .= "<br> Empty item title in row(s): " . $this->importModel->getWrongTitleRows();
                }

                if ($this->importModel->getWrongDescriptionRows() != "") {
                    $errorMessage .= "<br> Empty item description in row(s): " . $this->importModel->getWrongDescriptionRows();
                }
                $this->messageManager->addError(__($errorMessage));
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
