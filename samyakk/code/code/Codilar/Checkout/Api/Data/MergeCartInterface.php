<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Checkout\Api\Data;

interface MergeCartInterface
{
	/**
	 * @return string
	 */
	public function getQuoteId();


	/**
	 * @param string $quoteId
	 * @return $this
	 */
	public function setQuoteId($quoteId);

}
