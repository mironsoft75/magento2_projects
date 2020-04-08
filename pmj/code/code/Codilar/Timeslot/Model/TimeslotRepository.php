<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Model;


use Codilar\Timeslot\Api\TimeslotManagementInterface;
use Codilar\Timeslot\Api\TimeslotRepositoryInterface;
use Codilar\Timeslot\Model\ResourceModel\Timeslot\Collection;
use Codilar\Timeslot\Model\ResourceModel\Timeslot\CollectionFactory;
use Codilar\Timeslot\Model\Timeslot as Model;
use Codilar\Timeslot\Model\ResourceModel\Timeslot as ResourceModel;
use Codilar\Timeslot\Model\TimeslotFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class TimeslotRepository implements TimeslotRepositoryInterface
{
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var Timeslot
     */
    private $modelFactory;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var TimeslotManagementInterface
     */
    private $timeslotManagement;
    /**
     * @var DateTime
     */
    private $date;
    /**
     * @var TimezoneInterface
     */
    private $timezone;

    /**
     * TimeslotRepository constructor.
     * @param ResourceModel $resourceModel
     * @param TimeslotFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param TimeslotManagementInterface $timeslotManagement
     * @param DateTime $date
     * @param TimezoneInterface $timezone
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        TimeslotManagementInterface $timeslotManagement,
        DateTime $date,
        TimezoneInterface $timezone
    )
    {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->timeslotManagement = $timeslotManagement;
        $this->date = $date;
        $this->timezone = $timezone;
    }

    /**
     * @param string $value
     * @param string $field
     * @return Model
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null)
    {
        $model = $this->create();
        $this->resourceModel->load($model, $value, $field);
        if (!$model->getId()) {
            throw NoSuchEntityException::singleField($field, $value);
        }
        return $model;
    }

    /**
     * @return Model
     */
    public function create()
    {
        return $this->modelFactory->create();
    }

    /**
     * @param Model $model
     * @return Model
     * @throws AlreadyExistsException
     */
    public function save(Model $model)
    {
        $this->resourceModel->save($model);
        return $model;
    }

    /**
     * @param Model $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(Model $model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $exception) {
            throw new LocalizedException(__("Error deleting Model with Id : " . $model->getId()));
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create();
    }

    public function getCurrentDay($day)
    {
        $weekday = array();
        $weekday['Mon'] = "Monday";
        $weekday['Tue'] = "Tuesday";
        $weekday['Wed'] = "Wednesday";
        $weekday['Thu'] = "Thursday";
        $weekday['Fri'] = "Friday";
        $weekday['Sat'] = "Saturday";
        $weekday['Sun'] = "Sunday";
        return $weekday[$day];
    }

    /**
     * @param string $currentDay
     * @return Collection|mixed
     */
    public function getSlotsByDay($currentDay)
    {
        $time = strtotime($currentDay);
        $currentDate = date('Y-m-d', $time);
        $today = date("Y-m-d");
        $date = strtotime($currentDate);
        $day = date("D", $date);
        $currentDay = $this->getCurrentDay($day);
        $currentDay = $this->date->date('N', $currentDay);
        if ($today == $currentDate) {
            $currentTime = $this->timezone->date()->format('G:i');
            return $this->getCollection()->addFieldToFilter('day', $currentDay)
                ->addFieldToFilter('start_time', ['gt' => $currentTime])->addFieldToFilter('is_active',1)->setOrder('start_time', 'ASC');

        } else {
            return $this->getCollection()->addFieldToFilter('day', $currentDay)->addFieldToFilter('is_active',1)->setOrder('start_time', 'ASC');
        }
    }

    /**
     * @param \Codilar\Timeslot\Api\Data\TimeslotInterface $slots
     * @return mixed
     */
    public function getSlotLabelsBySlots($slots)
    {
        $newslots = array();
        /** @var \Codilar\Timeslot\Api\Data\TimeslotInterface $slot */
        foreach ($slots as $slot) {
            array_push($newslots, $this->timeslotManagement->getSlotLabel($slot));
        }
        return $newslots;
    }


}