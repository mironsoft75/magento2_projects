<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Meta\Api\Data\MetaData;

interface MetaInterface
{
	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name);


	/**
	 * @return string
	 */
	public function getContent();


	/**
	 * @param string $content
	 * @return $this
	 */
	public function setContent($content);
}
