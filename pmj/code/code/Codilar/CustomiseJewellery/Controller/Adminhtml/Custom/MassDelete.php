<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 4/12/18
 * Time: 4:09 PM
 */
namespace Codilar\CustomiseJewellery\Controller\Adminhtml\Custom;

use Codilar\CustomiseJewellery\Model\CustomiseJewelleryFactory;
use Codilar\CustomiseJewellery\Model\ResourceModel\CustomiseJewellery\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package Codilar\CustomiseJewellery\Controller\Adminhtml\Custom
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
     * @var StoneAndMetalRatesFactory
     */
    private $customiseJewelleryFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param CustomiseJewelleryFactory $customiseJewelleryFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file,
        Filter $filter,
        CollectionFactory $collectionFactory,
        CustomiseJewelleryFactory $customiseJewelleryFactory
    )
    {
        $this->_fileSystem = $filesystem;
        $this->_file = $file;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
        $this->customiseJewelleryFactory = $customiseJewelleryFactory;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = 0;
        foreach ($collection->getItems() as $item) {
            $item = $this->customiseJewelleryFactory->create()->load($item->getEntityId());
            $item->delete();

            $collectionSize++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $collectionSize)
        );
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('jewel/custom/index');
    }
}