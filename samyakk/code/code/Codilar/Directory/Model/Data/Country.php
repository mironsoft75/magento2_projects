<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Directory\Model\Data;


use Codilar\Directory\Api\Data\CountryInterface;
use Magento\Framework\DataObject;

class Country extends DataObject implements CountryInterface
{

    /**
     * @return \Codilar\Directory\Api\Data\CountryDataInterface
     */
    public function getAllowedCountries()
    {
        return $this->getData('allowed_countries');
    }

    /**
     * @param \Codilar\Directory\Api\Data\CountryDataInterface $allowedCountries
     * @return $this
     */
    public function setAllowedCountries($allowedCountries)
    {
        return $this->setData('allowed_countries', $allowedCountries);
    }

    /**
     * @return \Codilar\Directory\Api\Data\CountryDataInterface
     */
    public function getDisAllowedCountries()
    {
        return $this->getData('dis_allowed_countries');
    }

    /**
     * @param \Codilar\Directory\Api\Data\CountryDataInterface $disAllowedCountries
     * @return $this
     */
    public function setDisAllowedCountries($disAllowedCountries)
    {
        return $this->setData('dis_allowed_countries', $disAllowedCountries);
    }
}