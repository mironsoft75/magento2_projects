<?php

namespace Codilar\CategoryApi\Api\CategoryPage\SortOptions;

interface DirectionsManagementInterface
{
    /**
     * @param string $key
     * @param string $value
     * @param string $sortName
     * @param string $sortType
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface[]
     */
    public function getDirections($key, $value, $sortName, $sortType);
}
