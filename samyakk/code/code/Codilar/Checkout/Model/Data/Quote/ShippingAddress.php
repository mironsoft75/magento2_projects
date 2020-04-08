<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Model\Data\Quote;

use Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface;

class ShippingAddress extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\Quote\ShippingAddressInterface
{
	/**
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->getData('first_name');
	}


	/**
	 * @param string $firstName
	 * @return $this
	 */
	public function setFirstName($firstName)
	{
		return $this->setData('first_name', $firstName);
	}


	/**
	 * @return string
	 */
	public function getLastName()
	{
		return $this->getData('last_name');
	}


	/**
	 * @param string $lastName
	 * @return $this
	 */
	public function setLastName($lastName)
	{
		return $this->setData('last_name', $lastName);
	}


	/**
	 * @return string
	 */
	public function getCompany()
	{
		return $this->getData('company');
	}


	/**
	 * @param string $company
	 * @return $this
	 */
	public function setCompany($company)
	{
		return $this->setData('company', $company);
	}


	/**
	 * @return string[]
	 */
	public function getStreet()
	{
		return $this->getData('street');
	}


	/**
	 * @param string[] $street
	 * @return $this
	 */
	public function setStreet($street)
	{
		return $this->setData('street', $street);
	}


	/**
	 * @return string
	 */
	public function getCity()
	{
		return $this->getData('city');
	}


	/**
	 * @param string $city
	 * @return $this
	 */
	public function setCity($city)
	{
		return $this->setData('city', $city);
	}


	/**
	 * @return string
	 */
	public function getState()
	{
		return $this->getData('state');
	}


	/**
	 * @param string $state
	 * @return $this
	 */
	public function setState($state)
	{
		return $this->setData('state', $state);
	}


	/**
	 * @return string
	 */
	public function getZipcode()
	{
		return $this->getData('zipcode');
	}


	/**
	 * @param string $zipcode
	 * @return $this
	 */
	public function setZipcode($zipcode)
	{
		return $this->setData('zipcode', $zipcode);
	}


	/**
	 * @return string
	 */
	public function getCountry()
	{
		return $this->getData('country');
	}


	/**
	 * @param string $country
	 * @return $this
	 */
	public function setCountry($country)
	{
		return $this->setData('country', $country);
	}


	/**
	 * @return string
	 */
	public function getTelephone()
	{
		return $this->getData('telephone');
	}


	/**
	 * @param string $telephone
	 * @return $this
	 */
	public function setTelephone($telephone)
	{
		return $this->setData('telephone', $telephone);
	}

    /**
     * @return int
     */
    public function getRegionId()
    {
        return $this->getData('region_id');
    }

    /**
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId)
    {
        return $this->setData('region_id', $regionId);
    }
}
