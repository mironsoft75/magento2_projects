<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 19/7/19
 * Time: 7:47 PM
 */

namespace Codilar\SearchApi\Api\Data;

interface SearchPageInterface
{
    /**
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[]
     */
    public function getProduct();

    /**
     * @param \Codilar\CategoryApi\Api\Data\CategoryPage\DetailsInterface[] $product
     * @return $this
     */
    public function setProduct($product);

    /**
     * @return int
     */
    public function getPage();

    /**
     * @param $pageNumber
     * @return $this
     */
    public function setPage($pageNumber);

    /**
     * @return int
     */
    public function getPerPage();

    /**
     * @param $pageSize
     * @return $this
     */
    public function setPerPage($pageSize);

    /**
     * @return int
     */
    public function getTotal();

    /**
     * @param $total
     * @return $this
     */
    public function setTotal($total);
}
