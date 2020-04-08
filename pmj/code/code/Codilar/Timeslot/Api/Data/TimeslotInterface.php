<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Api\Data;


use Codilar\Timeslot\Model\Timeslot;

interface TimeslotInterface
{
    /**
     * @param Timeslot $timeslot
     * @param string $date format 'Y-m-d'
     * @return $this
     */
    public function init(Timeslot $timeslot, $date);

    /**
     * @return int
     */
    public function getDay();

    /**
     * @return string
     */
    public function getStartTime();

    /**
     * @return string
     */
    public function getEndTime();

    /**
     * @return int
     */
    public function getOrderLimit();
}