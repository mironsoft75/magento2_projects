<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product\Configurations;

use Codilar\Product\Api\Data\Product\Configurations\OptionsInterface;

class Options extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface
{
	/**
	 * @return integer
	 */
	public function getOptionId()
	{
		return $this->getData('option_id');
	}


	/**
	 * @param integer $optionId
	 * @return $this
	 */
	public function setOptionId($optionId)
	{
		return $this->setData('option_id', $optionId);
	}

    /**
     * @return string
     */
    public function getOptionCode()
    {
        return $this->getData('option_code');
    }

    /**
     * @param string $optionCode
     * @return $this
     */
    public function setOptionCode($optionCode)
    {
        return $this->setData('option_code', $optionCode);
    }

	/**
	 * @return integer
	 */
	public function getOptionValue()
	{
		return $this->getData('option_value');
	}


	/**
	 * @param integer $optionValue
	 * @return $this
	 */
	public function setOptionValue($optionValue)
	{
		return $this->setData('option_value', $optionValue);
	}
}
