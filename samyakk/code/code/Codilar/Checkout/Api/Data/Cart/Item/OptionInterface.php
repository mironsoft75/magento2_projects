<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data\Cart\Item;


interface OptionInterface
{
    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value);
}