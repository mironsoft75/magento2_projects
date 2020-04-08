<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Api;


interface TimeslotManagementInterface
{
    /**
     * @param int $howManyDays
     * @return array
     */
    public function getAvailableTimeslots($howManyDays);

    /**
     * @param int $howManyDays
     * @param \Magento\Framework\Stdlib\DateTime\TimezoneInterface $currentDateTime
     * @return array
     */
    public function getAllTimeslots($howManyDays, $currentDateTime);

    /**
     * @param \Codilar\Timeslot\Api\Data\TimeslotInterface $slot
     * @return string
     */
    public function getSlotLabel($slot);
}