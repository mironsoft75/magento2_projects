<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 30/11/18
 * Time: 3:25 PM
 */

namespace Codilar\Timeslot\Api;


interface SlotsByDayInterface
{
    /**
     * @api
     * @param string $day
     * @return string[]
     */
    public function getSlots($day);
}