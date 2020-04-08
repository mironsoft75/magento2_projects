<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data;

interface ItemInterface
{
	/**
	 * @return string
	 */
	public function getSku();


	/**
	 * @param string $sku
	 * @return $this
	 */
	public function setSku($sku);


	/**
	 * @return integer
	 */
	public function getQty();


	/**
	 * @param integer $qty
	 * @return $this
	 */
	public function setQty($qty);


	/**
	 * @return \Codilar\Checkout\Api\Data\Item\CustomOptionsInterface[]
	 */
	public function getCustomOptions();


	/**
	 * @param \Codilar\Checkout\Api\Data\Item\CustomOptionsInterface[] $customOptions
	 * @return $this
	 */
	public function setCustomOptions($customOptions);


	/**
	 * @return \Codilar\Checkout\Api\Data\Item\ConfigurableOptionsInterface[]
	 */
	public function getConfigurableOptions();


	/**
	 * @param \Codilar\Checkout\Api\Data\Item\ConfigurableOptionsInterface[] $configurableOptions
	 * @return $this
	 */
	public function setConfigurableOptions($configurableOptions);

    /**
     * @return \Codilar\Checkout\Api\Data\Item\AdditionalOptionsInterface[]
     */
	public function getAdditionalOptions();

    /**
     * @param \Codilar\Checkout\Api\Data\Item\AdditionalOptionsInterface[] $additionalOptions
     * @return $this
     */
	public function setAdditionalOptions($additionalOptions);

    /**
     * @return string
     */
    public function getRemoteIp();


    /**
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp);
}
