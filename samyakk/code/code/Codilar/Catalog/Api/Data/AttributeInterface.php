<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Api\Data;


interface AttributeInterface
{
    /**
     * @return string
     */
    public function getAttributeCode();

    /**
     * @param string $attributeCode
     * @return $this
     */
    public function setAttributeCode($attributeCode);

    /**
     * @return boolean
     */
    public function getUsesSource();

    /**
     * @param boolean $usesSource
     * @return $this
     */
    public function setUsesSource($usesSource);

    /**
     * @return \Codilar\Catalog\Api\Data\AttributeOptionInterface[]
     */
    public function getOptions();

    /**
     * @param \Codilar\Catalog\Api\Data\AttributeOptionInterface[] $options
     * @return $this
     */
    public function setOptions($options);
}