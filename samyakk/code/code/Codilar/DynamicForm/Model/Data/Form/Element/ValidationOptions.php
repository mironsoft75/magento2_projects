<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Data\Form\Element;

use Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface;
use Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface;
use Magento\Framework\Api\AbstractExtensibleObject;

class ValidationOptions extends AbstractExtensibleObject implements ValidationOptionsInterface
{

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_get(self::LABEL);
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_get(self::VALUE);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }
}