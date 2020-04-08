<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Meta\Model\Data\MetaData;

class Meta extends \Magento\Framework\DataObject implements \Codilar\Meta\Api\Data\MetaData\MetaInterface
{
	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getData('name');
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		return $this->setData('name', $name);
	}


	/**
	 * @return string
	 */
	public function getContent()
	{
		return $this->getData('content');
	}


	/**
	 * @param string $content
	 * @return $this
	 */
	public function setContent($content)
	{
		return $this->setData('content', $content);
	}
}
