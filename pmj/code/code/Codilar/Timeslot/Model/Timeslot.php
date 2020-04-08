<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Model;


use Magento\Framework\Model\AbstractModel;
use Codilar\Timeslot\Model\ResourceModel\Timeslot as ResourceModel;

class Timeslot extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @return int
     */
    public function getDay() {
        return $this->getData('day');
    }

    /**
     * @param int $day
     * @return Timeslot
     */
    public function setDay($day) {
        return $this->setData('day', $day);
    }

    /**
     * @return string
     */
    public function getStartTime() {
        return $this->getData('start_time');
    }


    /**
     * @param string $start
     * @return Timeslot
     */
    public function setStartTime($start) {
        return $this->setData('start_time', $start);
    }

    /**
     * @return string
     */
    public function getEndTime() {
        return $this->getData('end_time');
    }

    /**
     * @param string $end
     * @return Timeslot
     */
    public function setEndTime($end) {
        return $this->setData('end_time', $end);
    }

    /**
     * @return int
     */
    public function getSlotOrderLimit() {
        return $this->getData('order_limit');
    }

    /**
     * @param int $orderlimit
     * @return Timeslot
     */
    public function setSlotOrderLimit($orderlimit) {
        return $this->setData('order_limit', $orderlimit);
    }

    /**
     * @return int
     */
    public function getIsActive() {
        return $this->getData('is_active');
    }

    /**
     * @param int $isActive
     * @return Timeslot
     */
    public function setIsActive($isActive) {
        return $this->setData('is_active', $isActive);
    }
}