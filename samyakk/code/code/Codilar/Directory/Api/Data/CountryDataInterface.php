<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Directory\Api\Data;


interface CountryDataInterface
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
    public function getCode();

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * @return boolean
     */
    public function getHasRegion();

    /**
     * @param boolean $hasRegion
     * @return $this
     */
    public function setHasRegion($hasRegion);

    /**
     * @return \Codilar\Directory\Api\Data\RegionInterface[]
     */
    public function getRegions();

    /**
     * @param \Codilar\Directory\Api\Data\RegionInterface[] $regions
     * @return $this
     */
    public function setRegions($regions);
}