<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Api\Data;


interface AttributeOptionInterface
{
    /**
     * @return int
     */
    public function getOptionId();

    /**
     * @param int $optionId
     * @return $this
     */
    public function setOptionId($optionId);

    /**
     * @return string
     */
    public function getOptionLabel();

    /**
     * @param string $optionLabel
     * @return $this
     */
    public function setOptionLabel($optionLabel);
}