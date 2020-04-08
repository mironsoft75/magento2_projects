<?php


namespace Codilar\CategoryApi\Api\CategoryPage;


interface SortOptionsManagementInterface
{
    /**
     * @param string $sort
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface[]
     */
    public function getSortData($sort);
}