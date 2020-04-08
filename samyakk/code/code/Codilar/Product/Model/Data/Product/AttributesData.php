<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Data\Product;


use Codilar\Product\Api\Data\Product\AttributesDataInterface;
use Magento\Framework\DataObject;

class AttributesData extends DataObject implements AttributesDataInterface
{

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->getData('code');
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        return $this->setData('code', $code);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getData('label');
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        return $this->setData('label', $label);
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->getData('value');
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        return $this->setData('value', $value);
    }
}