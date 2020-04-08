<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Model\Data;

class CategoryPage extends \Magento\Framework\DataObject implements \Codilar\CategoryApi\Api\Data\CategoryPageInterface
{
	/**
	 * @return string
	 */
	public function getStatus()
	{
		return $this->getData('status');
	}


	/**
	 * @param string $status
	 * @return $this
	 */
	public function setStatus($status)
	{
		return $this->setData('status', $status);
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->getData('name');
	}


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name)
	{
		return $this->setData('name', $name);
	}


	/**
	 * @return integer
	 */
	public function getTotal()
	{
		return $this->getData('total');
	}


	/**
	 * @param integer $total
	 * @return $this
	 */
	public function setTotal($total)
	{
		return $this->setData('total', $total);
	}


	/**
	 * @return integer
	 */
	public function getPage()
	{
		return $this->getData('page');
	}


	/**
	 * @param integer $page
	 * @return $this
	 */
	public function setPage($page)
	{
		return $this->setData('page', $page);
	}


	/**
	 * @return integer
	 */
	public function getPerPage()
	{
		return $this->getData('per_page');
	}


	/**
	 * @param integer $perPage
	 * @return $this
	 */
	public function setPerPage($perPage)
	{
		return $this->setData('per_page', $perPage);
	}


	/**
	 * @return string[]
	 */
	public function getResult()
	{
		return $this->getData('result');
	}


	/**
	 * @param string[] $result
	 * @return $this
	 */
	public function setResult($result)
	{
		return $this->setData('result', $result);
	}


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
	 */
	public function getDetails()
	{
		return $this->getData('details');
	}


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[] $details
	 * @return $this
	 */
	public function setDetails($details)
	{
		return $this->setData('details', $details);
	}


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterface[]
	 */
	public function getFilters()
	{
		return $this->getData('filters');
	}


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterface[] $filters
	 * @return $this
	 */
	public function setFilters($filters)
	{
		return $this->setData('filters', $filters);
	}


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface[]
	 */
	public function getSortOptions()
	{
		return $this->getData('sort_options');
	}


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface[] $sortOptions
	 * @return $this
	 */
	public function setSortOptions($sortOptions)
	{
		return $this->setData('sort_options', $sortOptions);
	}
}
