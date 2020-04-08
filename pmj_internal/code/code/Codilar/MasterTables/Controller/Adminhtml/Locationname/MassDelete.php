<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 4:32 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Locationname;

use Codilar\MasterTables\Model\LocationNameFactory;
use Codilar\MasterTables\Model\ResourceModel\LocationName\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Codilar\MasterTables\Api\LocationNameRepositoryInterface;
use Magento\Ui\Component\MassAction\Filter;

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
    /**
     * @var
     */
    private $variantNameFactory;
    /**
     * @var LocationNameRepositoryInterface
     */
    protected $locationNameRepository;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param LocationNameFactory $variantNameFactory
     * @param LocationNameRepositoryInterface $locationNameRepository
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file,
        Filter $filter,
        CollectionFactory $collectionFactory,
        LocationNameFactory $variantNameFactory,
        LocationNameRepositoryInterface $locationNameRepository
    )
    {
        $this->_fileSystem = $filesystem;
        $this->_file = $file;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->variantNameFactory = $variantNameFactory;
        $this->locationNameRepository = $locationNameRepository;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {

        /**
         * @var \Codilar\MasterTables\Model\ResourceModel\Locationname\Collection $collection
         */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection->getItems() as $item) {
            /**
             * @var \Codilar\MasterTables\Model\LocationName $item
             */
            $item = $this->variantNameFactory->create()
                ->load($item->getLocationId());
            $this->locationNameRepository->delete($item);
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $collectionSize)
        );
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');

    }
}