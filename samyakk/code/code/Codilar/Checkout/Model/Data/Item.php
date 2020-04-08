<?php

/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Model\Data;

use Codilar\Checkout\Api\Data\ItemInterface;

class Item extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\ItemInterface
{
	/**
	 * @return string
	 */
	public function getSku()
	{
		return $this->getData('sku');
	}


	/**
	 * @param string $sku
	 * @return $this
	 */
	public function setSku($sku)
	{
		return $this->setData('sku', $sku);
	}


	/**
	 * @return integer
	 */
	public function getQty()
	{
		return $this->getData('qty');
	}


	/**
	 * @param integer $qty
	 * @return $this
	 */
	public function setQty($qty)
	{
		return $this->setData('qty', $qty);
	}


	/**
	 * @return \Codilar\Checkout\Api\Data\Item\CustomOptionsInterface[]
	 */
	public function getCustomOptions()
	{
		return $this->getData('custom_options') ? $this->getData('custom_options') : [];
	}


	/**
	 * @param \Codilar\Checkout\Api\Data\Item\CustomOptionsInterface[] $customOptions
	 * @return $this
	 */
	public function setCustomOptions($customOptions)
	{
		return $this->setData('custom_options', $customOptions);
	}


	/**
	 * @return \Codilar\Checkout\Api\Data\Item\ConfigurableOptionsInterface[]
	 */
	public function getConfigurableOptions()
	{
		return $this->getData('configurable_options') ? $this->getData('configurable_options') : [];
	}


	/**
	 * @param \Codilar\Checkout\Api\Data\Item\ConfigurableOptionsInterface[] $configurableOptions
	 * @return $this
	 */
	public function setConfigurableOptions($configurableOptions)
	{
		return $this->setData('configurable_options', $configurableOptions);
	}

    /**
     * @return \Codilar\Checkout\Api\Data\Item\AdditionalOptionInterface[]
     */
    public function getAdditionalOptions()
    {
        return $this->getData('additional_options') ? $this->getData('additional_options') : [];
    }

    /**
     * @param \Codilar\Checkout\Api\Data\Item\AdditionalOptionInterface[] $additionalOptions
     * @return $this
     */
    public function setAdditionalOptions($additionalOptions)
    {
        return $this->setData('additional_options', $additionalOptions);
    }

    /**
     * @return string
     */
    public function getRemoteIp()
    {
        return $this->getData('remote_ip');
    }

    /**
     * @param string $remoteIp
     * @return $this
     */
    public function setRemoteIp($remoteIp)
    {
        return $this->setData('remote_ip', $remoteIp);
    }
}
