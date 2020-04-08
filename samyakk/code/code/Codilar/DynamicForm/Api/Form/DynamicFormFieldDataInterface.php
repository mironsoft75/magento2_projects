<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Form;


interface DynamicFormFieldDataInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @param string $key
     * @return \Codilar\DynamicForm\Api\Form\DynamicFormFieldDataInterface
     */
    public function setKey($key);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return \Codilar\DynamicForm\Api\Form\DynamicFormFieldDataInterface
     */
    public function setValue($value);


}