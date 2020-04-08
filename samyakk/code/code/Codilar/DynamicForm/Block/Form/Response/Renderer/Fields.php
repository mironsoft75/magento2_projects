<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Form\Response\Renderer;


use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;

class Fields extends Template
{
    /**
     * @return string[] name1:value1,name2:value2, etc.
     */
    public function getFields()
    {
        $fields = $this->getData('fields');
        return $fields instanceof DataObject ? $fields->getData() : [];
    }

    public function getLabel($key)
    {
        return $this->getData("field-$key") ?: $key;
    }
}