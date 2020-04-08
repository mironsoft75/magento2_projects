<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data\CategoryPage\Details;

interface AdditionalAttributesInterface
{
	/**
	 * @return integer
	 */
	public function getAttributeId();


	/**
	 * @param integer $attributeId
	 * @return $this
	 */
	public function setAttributeId($attributeId);


	/**
	 * @return integer
	 */
	public function getAttributeValue();


	/**
	 * @param integer $attributeValue
	 * @return $this
	 */
	public function setAttributeValue($attributeValue);


	/**
	 * @return string
	 */
	public function getAttributeName();


	/**
	 * @param string $attributeName
	 * @return $this
	 */
	public function setAttributeName($attributeName);


	/**
	 * @return string
	 */
	public function getAttributeValueLabel();


	/**
	 * @param string $attributeValueLabel
	 * @return $this
	 */
	public function setAttributeValueLabel($attributeValueLabel);
}
