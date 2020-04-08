<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Form;


use Magento\Framework\Model\AbstractModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element as ResourceModel;

class Element extends AbstractModel
{
    const OPTIONS_JSON = "options_json";

    const VALIDATION_JSON = "validation_json";

    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}