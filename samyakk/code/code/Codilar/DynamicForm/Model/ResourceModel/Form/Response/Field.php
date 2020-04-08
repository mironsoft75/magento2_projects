<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\ResourceModel\Form\Response;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Field extends AbstractDb
{

    const TABLE_NAME = "codilar_dynamic_form_response_field";
    const ID_FIELD_NAME = "id";

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