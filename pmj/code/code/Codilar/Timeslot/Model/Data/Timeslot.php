<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Model\Data;


use Codilar\Timeslot\Api\Data\TimeslotInterface;
use Magento\Framework\DataObject;
use Codilar\Timeslot\Model\Source\Day;

class Timeslot extends DataObject implements TimeslotInterface
{
    /**
     * @var Day
     */
    private $day;

    /**
     * Timeslot constructor.
     * @param Day $day
     * @param array $data
     */
    public function __construct(
        Day $day,
        array $data = [])
    {
        parent::__construct($data);
        $this->day = $day;
    }

    /**
     * @param \Codilar\Timeslot\Model\Timeslot $timeslot
     * @param string $date format 'Y-m-d'
     * @return \Codilar\Timeslot\Model\Timeslot
     */
    public function init(\Codilar\Timeslot\Model\Timeslot $timeslot, $date)
    {
        $this->setData('start_time', $this->getStartTime());
        $this->setData('end_time', $this->getEndTime());
        $this->setData('order_limit', $this->getOrderLimit());
    }

    /**
     * @return int
     */
    public function getDay()
    {
        return $this->getDay('day');
    }

    /**
     * @return string
     */
    public function getStartTime()
    {
        return $this->getStartTime('start_time');
    }

    /**
     * @return string
     */
    public function getEndTime()
    {
        return $this->getEndTime('end_time');
    }

    public function getOrderLimit()
    {
        return $this->getOrderLimit('order_limit');
    }
}