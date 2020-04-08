<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class CheckoutPaypal extends AbstractDb
{

    const TABLE_NAME = "codilar_checkout_paypal";
    const ID_FIELD_NAME = "entity_id";

    /**
     * Resource initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(self::TABLE_NAME, self::ID_FIELD_NAME);
    }
}