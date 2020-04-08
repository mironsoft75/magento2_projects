<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Meta\Model\Data;

class MetaData extends \Magento\Framework\DataObject implements \Codilar\Meta\Api\Data\MetaDataInterface
{
	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getData('title');
	}


	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		return $this->setData('title', $title);
	}


	/**
	 * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface[]
	 */
	public function getMeta()
	{
		return $this->getData('meta');
	}


	/**
	 * @param \Codilar\Meta\Api\Data\MetaData\MetaInterface[] $meta
	 * @return $this
	 */
	public function setMeta($meta)
	{
		return $this->setData('meta', $meta);
	}
}
