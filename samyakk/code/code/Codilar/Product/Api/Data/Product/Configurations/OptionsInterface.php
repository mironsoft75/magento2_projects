<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product\Configurations;

interface OptionsInterface
{
	/**
	 * @return integer
	 */
	public function getOptionId();


	/**
	 * @param integer $optionId
	 * @return $this
	 */
	public function setOptionId($optionId);

    /**
     * @return string
     */
	public function getOptionCode();

    /**
     * @param string $optionCode
     * @return $this
     */
	public function setOptionCode($optionCode);

	/**
	 * @return integer
	 */
	public function getOptionValue();


	/**
	 * @param integer $optionValue
	 * @return $this
	 */
	public function setOptionValue($optionValue);
}
