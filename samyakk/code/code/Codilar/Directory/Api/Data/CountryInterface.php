<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Directory\Api\Data;


interface CountryInterface
{
    /**
     * @return \Codilar\Directory\Api\Data\CountryDataInterface[]
     */
    public function getAllowedCountries();

    /**
     * @param \Codilar\Directory\Api\Data\CountryDataInterface[] $allowedCountries
     * @return $this
     */
    public function setAllowedCountries($allowedCountries);

    /**
     * @return \Codilar\Directory\Api\Data\CountryDataInterface[]
     */
    public function getDisAllowedCountries();

    /**
     * @param \Codilar\Directory\Api\Data\CountryDataInterface[] $disAllowedCountries
     * @return $this
     */
    public function setDisAllowedCountries($disAllowedCountries);
}