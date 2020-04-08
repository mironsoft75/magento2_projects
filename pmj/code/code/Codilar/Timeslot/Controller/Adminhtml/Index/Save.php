<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Controller\Adminhtml\Index;


use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Codilar\Timeslot\Model\ResourceModel\Timeslot as TimeslotResource;
use Codilar\Timeslot\Model\ResourceModel\Timeslot\CollectionFactory as TimeslotCollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;

class Save extends Action
{
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;
    /**
     * @var TimeslotCollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param TimeslotRepositoryInterface $timeslotRepository
     * @param TimeslotCollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     */
    public function __construct(
        Action\Context $context,
        TimeslotRepositoryInterface $timeslotRepository,
        TimeslotCollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor
    )
    {
        parent::__construct($context);
        $this->timeslotRepository = $timeslotRepository;
        $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $collection = $this->collectionFactory->create()->addFieldToFilter('day',$data['day'])->addFieldToFilter('timeslot_id', ["neq" => $data['timeslot_id']])->getData();
            $startTime = strtotime($data['start_time']);
            $endTime = strtotime($data['end_time']);
            $idField = TimeslotResource::ID_FIELD;

            if (!preg_match("/^(?:2[0-3]|[01][0-9]|10):([0-5][0-9])$/", $data['start_time'])) {
                $this->messageManager->addErrorMessage("Incorrect format From time. Use the 24-hour format HH:MM");
                return $resultRedirect->setPath('codilartimeslot/index/edit', [$idField => $this->getRequest()->getParam($idField)]);
            }

            if (!preg_match("/^(?:2[0-3]|[01][0-9]|10):([0-5][0-9])$/", $data['end_time'])) {
                $this->messageManager->addErrorMessage("Incorrect format To time. Use the 24-hour format HH:MM");
                return $resultRedirect->setPath('codilartimeslot/index/edit', [$idField => $this->getRequest()->getParam($idField)]);
            }

            if ($startTime >= $endTime) {
                $this->messageManager->addErrorMessage("Incorrect time given. From time cannot be greater than or equal to To time");
                return $resultRedirect->setPath('codilartimeslot/index/edit', [$idField => $this->getRequest()->getParam($idField)]);
            }

            foreach ($collection as $timeslot) {
                $collectionStartTime = strtotime($timeslot['start_time']);
                $collectionEndTime = strtotime($timeslot['end_time']);
                if(!(($startTime<$collectionStartTime && $endTime<=$collectionStartTime)||($startTime>=$collectionEndTime && $endTime>$collectionEndTime))) {
                    $this->messageManager->addErrorMessage("Timeslot not applicable");
                    return $resultRedirect->setPath('codilartimeslot/index/edit', [$idField => $this->getRequest()->getParam($idField)]);
                }
            }

//            if($data['order_limit']<0) {
//                $this->messageManager->addErrorMessage("No negative slot limit accepted");
//                return $resultRedirect->setPath('codilartimeslot/index/edit', [$idField => $this->getRequest()->getParam($idField)]);
//            }

            $model = $this->timeslotRepository->create();
            if (empty($data[$idField])) {
                $data[$idField] = null;
            }
            try {
                $model->setData($data);
                $this->timeslotRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the timeslot'));
            }
            catch (\Exception $exception) {
                $this->messageManager->addErrorMessage($exception, __('Something went wrong while saving the slot.'));
            }
        }
        return $resultRedirect->setPath('*/*/');
    }
}