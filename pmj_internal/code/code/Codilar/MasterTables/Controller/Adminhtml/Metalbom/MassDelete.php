<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 14/5/19
 * Time: 4:32 PM
 */

namespace Codilar\MasterTables\Controller\Adminhtml\Metalbom;

use Codilar\MasterTables\Model\MetalBomFactory;
use Codilar\MasterTables\Model\ResourceModel\MetalBom\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Codilar\MasterTables\Api\MetalBomRepositoryInterface;
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
    private $variantNameFactory;
    /**
     * @var MetalBomRepositoryInterface
     */
    protected $metalBomRepository;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Filesystem $filesystem
     * @param File $file
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param MetalBomFactory $variantNameFactory
     * @param MetalBomRepositoryInterface $metalBomRepository
     */
    public function __construct(
        Context $context,
        Filesystem $filesystem,
        File $file,
        Filter $filter,
        CollectionFactory $collectionFactory,
        MetalBomFactory $variantNameFactory,
        MetalBomRepositoryInterface $metalBomRepository
    )
    {
        $this->_fileSystem = $filesystem;
        $this->_file = $file;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
        $this->variantNameFactory = $variantNameFactory;
        $this->metalBomRepository = $metalBomRepository;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {

        /**
         * @var \Codilar\MasterTables\Model\ResourceModel\Metalbom\Collection $collection
         */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = $collection->getSize();
        foreach ($collection->getItems() as $item) {
            /**
             * @var \Codilar\MasterTables\Model\MetalBom $item
             */
            $item = $this->variantNameFactory->create()
                ->load($item->getMetalBomId());
            $this->metalBomRepository->delete($item);
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $collectionSize)
        );
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('*/*/');

    }
}