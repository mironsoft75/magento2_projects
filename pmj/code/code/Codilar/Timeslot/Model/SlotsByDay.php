<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 30/11/18
 * Time: 3:27 PM
 */

namespace Codilar\Timeslot\Model;


use Codilar\Timeslot\Api\SlotsByDayInterface;
use Codilar\Timeslot\Api\TimeslotRepositoryInterface;

class SlotsByDay implements SlotsByDayInterface
{
    /**
     * @var TimeslotRepositoryInterface
     */
    private $timeslotRepository;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * SlotsByDay constructor.
     * @param TimeslotRepositoryInterface $timeslotRepository
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     */
    public function __construct(
        TimeslotRepositoryInterface $timeslotRepository,
        \Magento\Framework\Stdlib\DateTime\DateTime $date
    )
    {
        $this->timeslotRepository = $timeslotRepository;
        $this->date = $date;
    }

    /**
     * @api
     * @param string $day
     * @return string[]
     */
    public function getSlots($day)
    {
        $slots = $this->timeslotRepository->getSlotsByDay($day);
        return ($this->timeslotRepository->getSlotLabelsBySlots($slots));
    }

}