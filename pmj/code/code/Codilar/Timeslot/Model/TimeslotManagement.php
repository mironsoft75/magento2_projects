<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Model;


use Codilar\Timeslot\Api\TimeslotManagementInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Codilar\Timeslot\Model\ResourceModel\Timeslot\CollectionFactory as TimeslotCollectionFactory;
use Codilar\Timeslot\Model\ResourceModel\Timeslot\Collection as TimeslotCollection;
use Magento\Store\Model\ScopeInterface;

class TimeslotManagement implements TimeslotManagementInterface
{

    /**
     * @var TimezoneInterface
     */
    private $timezone;
    /**
     * @var TimeslotCollectionFactory
     */
    private $timeslotCollectionFactory;

    const XML_PATH_CUSTOMER_THRESHOLD = 'timeslot/timeslot/customerthreshold';
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Data constructor.
     * @param TimezoneInterface $timezone
     * @param TimeslotCollectionFactory $timeslotCollectionFactory
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        TimezoneInterface $timezone,
        TimeslotCollectionFactory $timeslotCollectionFactory,
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->timezone = $timezone;
        $this->timeslotCollectionFactory = $timeslotCollectionFactory;
        $this->_storeScope = ScopeInterface::SCOPE_STORE;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Returns available timeslots with respect to customer threshold
     *
     * @param int $howManyDays
     * @return array
     * @throws \Exception
     */
    public function getAvailableTimeslots($howManyDays = 2) {
        $currentDateTime = $this->getCurrentDateTime();
        $customerThreshold = $this->getCustomerThreshold();
        $interval_spec = 'PT'.$customerThreshold.'M';
        $currentThresholdTime = $currentDateTime->add(new \DateInterval($interval_spec))->format('G:i');
        return $this->getTimeslots($howManyDays, $currentThresholdTime);
    }

    /**
     * Returns all the timeslots
     *
     * @param int $howManyDays
     * @param  \Magento\Framework\Stdlib\DateTime\TimezoneInterface $currentDateTime
     * @return array
     * @throws \Exception
     */
    public function getAllTimeslots($howManyDays = 1, $currentDateTime) {
        //$currentDateTime = $this->getCurrentDateTime();
        $currentTime = $currentDateTime->format('G:i');
        return $this->getTimeslots($howManyDays, $currentTime);
    }

    /**
     * Returns Timeslots array using Timeslot collection
     *
     * @param int $howManyDays
     * @param string $currentTime
     * @return array
     * @throws \Exception
     */
    public function getTimeslots($howManyDays, $currentTime) {
        $daysCount = 0;
        $currentDateTime = $this->getCurrentDateTime();
//        $currentDateTime = $this->getSelectDateDay();
        $currentDate = $currentDateTime->format('d F Y');
        $currentDay = $currentDateTime->format('N');
        $currentDayLabel = $currentDateTime->format('l');
        $result_slots=array();
        $today = 0;

        for($i= 0; $i < $howManyDays*7 && $daysCount < $howManyDays; $i++) {
            $today++;
            $collection = $this->timeslotCollectionFactory->create();
            $collection->setOrder("start_time", "ASC");
            if($today == 1) {
                $collection->addFieldToFilter('day', $currentDay)
                    ->addFieldToFilter('start_time', ['gt' => $currentTime])
                    ->addFieldToFilter('is_active', '1')->count();
                $result_slots[] = $this->createResultSlots($collection, $currentDateTime, "Today", $currentDate);
                $daysCount++;
            }
            else if($today == 2){
                if($collection->addFieldToFilter('day', $currentDay)
                    ->addFieldToFilter('is_active', '1')->count()) {
                    $result_slots[] = $this->createResultSlots($collection, $currentDateTime, "Tomorrow", $currentDate);
                    $daysCount++;
                }
            }
            else {
                if($collection->addFieldToFilter('day', $currentDay)
                    ->addFieldToFilter('is_active', '1')->count()) {
                    $result_slots[] = $this->createResultSlots($collection, $currentDateTime, $currentDayLabel, $currentDate);
                    $daysCount++;
                }
            }
            $nextDateTime = $currentDateTime->add(new \DateInterval('P1D'));
            $nextDay = $nextDateTime->format('N');
            $currentDate = $nextDateTime->format('d F Y');
            $currentDay = $nextDay;
            $currentDayLabel = $nextDateTime->format('l');
        }
        return $result_slots;
    }

    /**
     * Returns customer threshold from configuration
     *
     * @return int
     */
    public function getCustomerThreshold() {
        return $this->scopeConfig->getValue(self::XML_PATH_CUSTOMER_THRESHOLD, $this->_storeScope);
    }

    /**
     * Creates timeslots array
     *
     * @param TimeslotCollection $collection
     * @param \DateTime $currentDateTime
     * @param string $currentDayLabel
     * @param string $currentDate
     * @return array
     */
    public function createResultSlots(
        TimeslotCollection $collection,
        \DateTime $currentDateTime,
        string $currentDayLabel,
        string $currentDate
    )
    {
        /** @var \Codilar\Timeslot\Model\Timeslot[]  $slots */
        $slots = [];
        foreach ($collection as $slot) {
            /** @var \Codilar\Timeslot\Model\Timeslot $slot */
            $slot->setData('slot_value', $currentDateTime->format('Y-m-d').' '.$slot->getStartTime()."-".$slot->getEndTime());
            $slot->setData('slot_label', $this->getSlotLabel($slot));
            $slots[] = $slot->getData();
        }

        $result_slots = [
            "label"         => $currentDayLabel,
            "date"          => $currentDate,
            "slots"         => $slots
        ];
        return $result_slots;
    }

    /**
     * @param \Codilar\Timeslot\Model\Timeslot $slot
     * @return string
     */
    public function getSlotLabel($slot) {
        $startTime = date('h:i a', strtotime($slot->getStartTime()));
        $endTime = date('h:i a', strtotime($slot->getEndTime()));
        return $startTime." - ".$endTime;
    }


    /**
     * @return \DateTime
     */
    public function getCurrentDateTime() {
        return $this->timezone->date();
    }

    public function getSelectDateDay() {
//        return $this->timezone->
    }
}
