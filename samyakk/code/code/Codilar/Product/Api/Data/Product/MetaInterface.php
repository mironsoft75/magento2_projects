<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Api\Data\Product;

interface MetaInterface
{
	/**
	 * @return string
	 */
	public function getMetaTitle();


	/**
	 * @param string $metaTitle
	 * @return $this
	 */
	public function setMetaTitle($metaTitle);


	/**
	 * @return string
	 */
	public function getMetaKeywords();


	/**
	 * @param string $metaKeywords
	 * @return $this
	 */
	public function setMetaKeywords($metaKeywords);


	/**
	 * @return string
	 */
	public function getMetaDescription();


	/**
	 * @param string $metaDescription
	 * @return $this
	 */
	public function setMetaDescription($metaDescription);
}
