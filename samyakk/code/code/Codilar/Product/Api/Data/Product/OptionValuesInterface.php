<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product;

interface OptionValuesInterface
{
	/**
	 * @return integer
	 */
	public function getId();


	/**
	 * @param integer $id
	 * @return $this
	 */
	public function setId($id);

    /**
     * @return string
     */
	public function getCode();

    /**
     * @param string $code
     * @return $this
     */
	public function setCode($code);

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
	public function getType();

    /**
     * @param string $type
     * @return $this
     */
	public function setType($type);

	/**
	 * @return \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface[]
	 */
	public function getValues();


	/**
	 * @param \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface[] $values
	 * @return $this
	 */
	public function setValues($values);
}
