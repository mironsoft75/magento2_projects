<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Api\Api\Data;

interface EntityDataInterface
{
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
	 * @return string
	 */
	public function getIdentifier();


	/**
	 * @param string $identifier
	 * @return $this
	 */
	public function setIdentifier($identifier);

    /**
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     */
	public function getMetaData();

    /**
     * @param \Codilar\Meta\Api\Data\MetaDataInterface $metaData
     * @return $this
     */
	public function setMetaData($metaData);
}
