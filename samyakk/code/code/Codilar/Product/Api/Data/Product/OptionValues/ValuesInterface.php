<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product\OptionValues;

interface ValuesInterface
{
	/**
	 * @return integer
	 */
	public function getValueId();


	/**
	 * @param integer $valueId
	 * @return $this
	 */
	public function setValueId($valueId);


	/**
	 * @return integer
	 */
	public function getValueType();


	/**
	 * @param integer $valueType
	 * @return $this
	 */
	public function setValueType($valueType);


	/**
	 * @return string
	 */
	public function getValueLabel();


	/**
	 * @param string $valueLabel
	 * @return $this
	 */
	public function setValueLabel($valueLabel);


	/**
	 * @return string
	 */
	public function getValueTitle();


	/**
	 * @param string $valueTitle
	 * @return $this
	 */
	public function setValueTitle($valueTitle);
}
