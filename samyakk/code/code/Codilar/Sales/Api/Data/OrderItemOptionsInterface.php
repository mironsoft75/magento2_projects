<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;


interface OrderItemOptionsInterface
{
    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface
     */
    public function setValue($value);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return \Codilar\Sales\Api\Data\OrderItemOptionsInterface
     */
    public function setLabel($label);
}