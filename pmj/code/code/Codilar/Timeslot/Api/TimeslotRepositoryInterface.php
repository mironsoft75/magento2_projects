<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Api;

use Codilar\Timeslot\Model\Timeslot as Model;
use Codilar\Timeslot\Model\ResourceModel\Timeslot\Collection;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface TimeslotRepositoryInterface
{
    /**
     * @param string $value
     * @param string $field
     * @return Model
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null);

    /**
     * @return Model
     */
    public function create();

    /**
     * @param Model $model
     * @return Model
     * @throws AlreadyExistsException
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(Model $model);

    /**
     * @return collection
     */
    public function getCollection();


    /**
     * @param string $currentDay
     * @return mixed
     */
    public function getSlotsByDay($currentDay);


    /**
     * @param \Codilar\Timeslot\Api\Data\TimeslotInterface $slots
     * @return mixed
     */
    public function getSlotLabelsBySlots($slots);
}