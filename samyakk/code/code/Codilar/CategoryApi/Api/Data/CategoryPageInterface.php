<?php

/**
 * Made with m2modulemaker
 *
 * @package M2ModuleMaker
 * @author Jayanka Ghosh
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 */

namespace Codilar\CategoryApi\Api\Data;

interface CategoryPageInterface
{
	/**
	 * @return string
	 */
	public function getStatus();


	/**
	 * @param string $status
	 * @return $this
	 */
	public function setStatus($status);


	/**
	 * @return string
	 */
	public function getName();


	/**
	 * @param string $name
	 * @return $this
	 */
	public function setName($name);


	/**
	 * @return integer
	 */
	public function getTotal();


	/**
	 * @param integer $total
	 * @return $this
	 */
	public function setTotal($total);


	/**
	 * @return integer
	 */
	public function getPage();


	/**
	 * @param integer $page
	 * @return $this
	 */
	public function setPage($page);


	/**
	 * @return integer
	 */
	public function getPerPage();


	/**
	 * @param integer $perPage
	 * @return $this
	 */
	public function setPerPage($perPage);


	/**
	 * @return string[]
	 */
	public function getResult();


	/**
	 * @param string[] $result
	 * @return $this
	 */
	public function setResult($result);


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
	 */
	public function getDetails();


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[] $details
	 * @return $this
	 */
	public function setDetails($details);


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterface[]
	 */
	public function getFilters();


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\FiltersInterface[] $filters
	 * @return $this
	 */
	public function setFilters($filters);


	/**
	 * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface[]
	 */
	public function getSortOptions();


	/**
	 * @param \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface[] $sortOptions
	 * @return $this
	 */
	public function setSortOptions($sortOptions);
}
