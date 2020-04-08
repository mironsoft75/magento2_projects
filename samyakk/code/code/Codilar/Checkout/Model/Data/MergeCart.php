<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Model\Data;

class MergeCart extends \Magento\Framework\DataObject implements \Codilar\Checkout\Api\Data\MergeCartInterface
{
	/**
	 * @return string
	 */
	public function getQuoteId()
	{
		return $this->getData('quote_id');
	}


	/**
	 * @param string $quoteId
	 * @return $this
	 */
	public function setQuoteId($quoteId)
	{
		return $this->setData('quote_id', $quoteId);
	}
}
