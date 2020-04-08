<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Meta\Api\Data;

interface MetaDataInterface
{
	/**
	 * @return string
	 */
	public function getTitle();


	/**
	 * @param string $title
	 * @return $this
	 */
	public function setTitle($title);


	/**
	 * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface[]
	 */
	public function getMeta();


	/**
	 * @param \Codilar\Meta\Api\Data\MetaData\MetaInterface[] $meta
	 * @return $this
	 */
	public function setMeta($meta);
}
