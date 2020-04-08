<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Model\Order\Item;


use Codilar\Sales\Api\Data\OrderItemFormsInterface;
use Magento\Framework\DataObject;

class Forms extends DataObject implements OrderItemFormsInterface
{

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getData("identifier");
    }

    /**
     * @param string $identifier
     * @return \Codilar\Sales\Api\Data\OrderItemFormsInterface
     */
    public function setIdentifier($identifier)
    {
        return $this->setData("identifier", $identifier);
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
     * @return \Codilar\Sales\Api\Data\OrderItemFormsInterface
     */
    public function setLabel($label)
    {
        return $this->setData("label", $label);
    }
}