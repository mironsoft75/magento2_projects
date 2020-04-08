<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\FormResponse\View;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field\CollectionFactory;
use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Filesystem\DirectoryList;

class File extends Action
{

    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_response";
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * File constructor.
     * @param CollectionFactory $collectionFactory
     * @param Filesystem $filesystem
     * @param Action\Context $context
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        Filesystem $filesystem,
        Action\Context $context
    )
    {
        parent::__construct($context);
        $this->collectionFactory = $collectionFactory;
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $fileName
     * @return string
     */
    protected function getMimeType($fileName)
    {
        $mimeTypes = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',
            'docx' => 'application/msword',
            'xlsx' => 'application/vnd.ms-excel',
            'pptx' => 'application/vnd.ms-powerpoint',


            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'csv' => 'text/csv'
        );
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        return array_key_exists($extension, $mimeTypes) ? $mimeTypes[$extension] : $mimeTypes['txt'];

    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function execute()
    {
        $fieldId = $this->getRequest()->getParam('id');
        $field = $this->collectionFactory->create()
            ->addFieldToFilter(FieldInterface::ID, $fieldId)
            ->addFieldToFilter(FieldInterface::TYPE, ElementInterface::TYPE_FILE)
            ->getFirstItem();
        if ($field->getId()) {
            $filePath = $this->filesystem
                ->getDirectoryWrite(DirectoryList::VAR_DIR)
                ->getAbsolutePath() . $field->getData(FieldInterface::VALUE);
            if (file_exists($filePath)) {
                $fileName = explode(DIRECTORY_SEPARATOR, $field->getData(FieldInterface::VALUE));
                $fileName = $fileName[count($fileName) - 1];
                $mimeType = $this->getMimeType($filePath);
                header("Content-type: $mimeType; charset: UTF-8");
                header('Content-Disposition: inline; filename="'.$fileName);
                readfile($filePath);
                exit(0);
            }
        }
        throw new NotFoundException(__("Not found"));
    }
}