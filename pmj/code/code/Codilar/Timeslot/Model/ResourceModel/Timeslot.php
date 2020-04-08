<?php
/**
 * @package     eat
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Timeslot\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Timeslot extends AbstractDb
{

    const TABLE_NAME = "codilar_timeslot";
    const ID_FIELD = "timeslot_id";

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME,self::ID_FIELD);
    }
}