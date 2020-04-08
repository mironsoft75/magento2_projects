<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Directory\Model\Data;

use Codilar\Directory\Api\Data\CountryDataInterface;
use Codilar\Directory\Api\Data\RegionInterface;
use Magento\Framework\DataObject;

class CountryData extends DataObject implements CountryDataInterface
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
    public function getCode()
    {
        return $this->getData('code');
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code)
    {
        return $this->setData('code', $code);
    }

    /**
     * @return boolean
     */
    public function getHasRegion()
    {
        return $this->getData('has_region');
    }

    /**
     * @param boolean $hasRegion
     * @return $this
     */
    public function setHasRegion($hasRegion)
    {
        return $this->setData('has_region', $hasRegion);
    }

    /**
     * @return \Codilar\Directory\Api\Data\RegionInterface[]
     */
    public function getRegions()
    {
        return $this->getData('regions');
    }

    /**
     * @param \Codilar\Directory\Api\Data\RegionInterface[] $regions
     * @return $this
     */
    public function setRegions($regions)
    {
        return $this->setData('regions', $regions);
    }
}
