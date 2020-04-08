<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Api\Data\Quote;

interface ShippingAddressInterface
{
	/**
	 * @return string
	 */
	public function getFirstName();


	/**
	 * @param string $firstName
	 * @return $this
	 */
	public function setFirstName($firstName);


	/**
	 * @return string
	 */
	public function getLastName();


	/**
	 * @param string $lastName
	 * @return $this
	 */
	public function setLastName($lastName);


	/**
	 * @return string
	 */
	public function getCompany();


	/**
	 * @param string $company
	 * @return $this
	 */
	public function setCompany($company);


	/**
	 * @return string[]
	 */
	public function getStreet();


	/**
	 * @param string[] $street
	 * @return $this
	 */
	public function setStreet($street);


	/**
	 * @return string
	 */
	public function getCity();


	/**
	 * @param string $city
	 * @return $this
	 */
	public function setCity($city);


	/**
	 * @return string
	 */
	public function getState();


	/**
	 * @param string $state
	 * @return $this
	 */
	public function setState($state);

    /**
     * @return int
     */
    public function getRegionId();

    /**
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId);


	/**
	 * @return string
	 */
	public function getZipcode();


	/**
	 * @param string $zipcode
	 * @return $this
	 */
	public function setZipcode($zipcode);


	/**
	 * @return string
	 */
	public function getCountry();


	/**
	 * @param string $country
	 * @return $this
	 */
	public function setCountry($country);


	/**
	 * @return string
	 */
	public function getTelephone();


	/**
	 * @param string $telephone
	 * @return $this
	 */
	public function setTelephone($telephone);
}
