<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;


interface OrderAddressInterface
{
    /**
     * @return string
     */
    public function getFirstname();

    /**
     * @param string $firstname
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setFirstname($firstname);

    /**
     * @return string
     */
    public function getLastname();

    /**
     * @param string $lastname
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setLastname($lastname);

    /**
     * @return string
     */
    public function getEmail();

    /**
     * @param string $email
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setEmail($email);

    /**
     * @return string
     */
    public function getCompany();

    /**
     * @param string $company
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setCompany($company);

    /**
     * @return string
     */
    public function getStreet();

    /**
     * @param string $street
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setStreet($street);

    /**
     * @return string
     */
    public function getCity();

    /**
     * @param string $city
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setCity($city);

    /**
     * @return string
     */
    public function getRegion();

    /**
     * @param string $region
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setRegion($region);

    /**
     * @return string
     */
    public function getCountryId();

    /**
     * @param string $countryId
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setCountryId($countryId);

    /**
     * @return string
     */
    public function getTelephone();

    /**
     * @param string $telephone
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setTelephone($telephone);

    /**
     * @return string
     */
    public function getPostcode();

    /**
     * @param string $postcode
     * @return \Codilar\Sales\Api\Data\OrderAddressInterface
     */
    public function setPostcode($postcode);
}