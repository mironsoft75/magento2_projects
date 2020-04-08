<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\Product\Model\Data\Product;

class Meta extends \Magento\Framework\DataObject implements \Codilar\Product\Api\Data\Product\MetaInterface
{
	/**
	 * @return string
	 */
	public function getMetaTitle()
	{
		return $this->getData('meta_title');
	}


	/**
	 * @param string $metaTitle
	 * @return $this
	 */
	public function setMetaTitle($metaTitle)
	{
		return $this->setData('meta_title', $metaTitle);
	}


	/**
	 * @return string
	 */
	public function getMetaKeywords()
	{
		return $this->getData('meta_keywords');
	}


	/**
	 * @param string $metaKeywords
	 * @return $this
	 */
	public function setMetaKeywords($metaKeywords)
	{
		return $this->setData('meta_keywords', $metaKeywords);
	}


	/**
	 * @return string
	 */
	public function getMetaDescription()
	{
		return $this->getData('meta_description');
	}


	/**
	 * @param string $metaDescription
	 * @return $this
	 */
	public function setMetaDescription($metaDescription)
	{
		return $this->setData('meta_description', $metaDescription);
	}
}
