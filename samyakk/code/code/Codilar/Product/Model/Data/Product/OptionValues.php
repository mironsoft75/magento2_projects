<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product;

use Codilar\Product\Api\Data\Product\OptionValuesInterface;

class OptionValues extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\OptionValuesInterface
{
	/**
	 * @return integer
	 */
	public function getId()
	{
		return $this->getData('id');
	}

	/**
	 * @param integer $id
	 * @return $this
	 */
	public function setId($id)
	{
		return $this->setData('id', $id);
	}

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
    public function getType()
    {
        return $this->getData('type');
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType($type)
    {
        return $this->setData('type', $type);
    }

	/**
	 * @return \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface[]
	 */
	public function getValues()
	{
		return $this->getData('values');
	}


	/**
	 * @param \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface[] $values
	 * @return $this
	 */
	public function setValues($values)
	{
		return $this->setData('values', $values);
	}
}
