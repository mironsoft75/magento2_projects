<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 4:32 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Metal;

use Codilar\MasterTables\Model\MetalFactory;
use Codilar\MasterTables\Model\ResourceModel\Metal\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Codilar\MasterTables\Api\MetalRepositoryInterface;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Driver\File;

/**
 * Class MassDelete
 * @package Codilar\Appointment\Controller\Adminhtml\Custom
 */
class MassDelete extends \Magento\Backend\App\Action
{
    /**
     * @var \Magento\Framework\Filesystem
     */
    protected $_fileSystem;
    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_file;
    /**
     * Massactions filter
     *
     * @var Filter
     */
    protected $filter;

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    private $metalFactory;
    /**
     * @var MetalRepositoryInterface
     */
    protected $metalRepositoryInterface;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param File $file
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param MetalFactory $metalFactory
     * @param MetalRepositoryInterface $metalRepositoryInterface
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        File $file,
        Filter $filter,
        CollectionFactory $collectionFactory,
        MetalFactory $metalFactory,
        MetalRepositoryInterface $metalRepositoryInterface

    )
    {
        $this->_fileSystem = $filesystem;
        $this->_file = $file;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
        $this->metalFactory = $metalFactory;
        $this->metalRepositoryInterface = $metalRepositoryInterface;

    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /**
         * @var \Codilar\MasterTables\Model\ResourceModel\Metal\Collection $collection
         */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection->getItems() as $item) {
            /**
             * @var \Codilar\MasterTables\Model\Metal $item
             */
            $item = $this->metalFactory->create()
                ->load($item->getMetalId());
            $this->metalRepositoryInterface->delete($item);
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $collectionSize)
        );
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');

    }
}