<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 2:50 PM
 */

namespace Codilar\Appointment\Model\ResourceModel;


class AppointmentRequest extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_idFieldName = 'request_id';
    protected $_date;

    /**
     * AppointmentCart constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $resourcePrefix
     */
    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context,
                                $resourcePrefix = null)
    {
        parent::__construct($context, $resourcePrefix);
    }
    protected function _construct()
    {
        $this->_init('codilar_appointment', 'request_id');
    }
}