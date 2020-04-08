<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Model\Order\Item;


use Codilar\Sales\Api\Data\OrderItemOptionsInterface;
use Magento\Framework\DataObject;

class Options extends DataObject implements OrderItemOptionsInterface
{

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->getData("value");
    }

    /**
     * @param string $value
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface
     */
    public function setValue($value)
    {
        return $this->setData("value", $value);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getData("label");
    }

    /**
     * @param string $label
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface
     */
    public function setLabel($label)
    {
        return $this->setData("label", $label);
    }
}