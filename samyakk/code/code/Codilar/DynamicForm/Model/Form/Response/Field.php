<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Form\Response;

use Magento\Framework\Model\AbstractModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field as ResourceModel;

class Field extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}