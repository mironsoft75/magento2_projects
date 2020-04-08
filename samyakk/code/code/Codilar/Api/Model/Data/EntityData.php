<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Api\Model\Data;

use Codilar\Api\Api\Data\EntityDataInterface;

class EntityData extends \Magento\Framework\DataObject implements \Codilar\Api\Api\Data\EntityDataInterface
{
	/**
	 * @return string
	 */
	public function getType()
	{
		return $this->getData('type');
	}


	/**
	 * @param string $type
	 * @return $this
	 */
	public function setType($type)
	{
		return $this->setData('type', $type);
	}


	/**
	 * @return string
	 */
	public function getIdentifier()
	{
		return $this->getData('identifier');
	}


	/**
	 * @param string $identifier
	 * @return $this
	 */
	public function setIdentifier($identifier)
	{
		return $this->setData('identifier', $identifier);
	}

    /**
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     */
    public function getMetaData()
    {
        return $this->getData('meta_data');
    }

    /**
     * @param \Codilar\Meta\Api\Data\MetaDataInterface $metaData
     * @return $this
     */
    public function setMetaData($metaData)
    {
        return $this->setData('meta_data', $metaData);
    }
}
