<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 5:12 PM
 */

namespace Codilar\Appointment\Model\ResourceModel\AppointmentRequest;


class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'request_id';
    protected $_eventPrefix = 'codilar_appointment_request_collection';
    protected $_eventObject = 'appointment_request_collection';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
         $this->_init('Codilar\Appointment\Model\AppointmentRequest', 'Codilar\Appointment\Model\ResourceModel\AppointmentRequest');
    }
}