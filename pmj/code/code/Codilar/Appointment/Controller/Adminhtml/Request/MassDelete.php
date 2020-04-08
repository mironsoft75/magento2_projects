<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 4/12/18
 * Time: 4:09 PM
 */

namespace Codilar\Appointment\Controller\Adminhtml\Request;

use Codilar\Appointment\Model\AppointmentRequestFactory;
use Codilar\Appointment\Model\ResourceModel\AppointmentRequest\CollectionFactory;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultFactory;
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
    private $appointmentRequestFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $file
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param AppointmentRequestFactory $appointmentRequestFactory
     */
    public function __construct(
        Context $context,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $file,
        Filter $filter,
        CollectionFactory $collectionFactory,
        AppointmentRequestFactory $appointmentRequestFactory
    )
    {
        $this->_fileSystem = $filesystem;
        $this->_file = $file;
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
        $this->appointmentRequestFactory = $appointmentRequestFactory;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /**
         * @var Codilar\Appointment\Model\ResourceModel\AppointmentRequest\CollectionFactory $collection
         */
        $collection = $this->filter->getCollection($this->collectionFactory->create());
        $collectionSize = 0;
        foreach ($collection->getItems() as $item) {
            $item = $this->appointmentRequestFactory->create()->load($item->getRequestId());
            $item->delete();
            $collectionSize++;
        }
        $this->messageManager->addSuccess(
            __('A total of %1 record(s) have been deleted.', $collectionSize)
        );
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('appointment/request/index');
    }
}