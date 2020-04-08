<?php

namespace Codilar\CategoryApi\Model\CategoryPage\SortOptions;

use Codilar\CategoryApi\Api\CategoryPage\SortOptions\DirectionsManagementInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterfaceFactory;

class DirectionsManagement implements DirectionsManagementInterface
{
    /**
     * @var DirectionsInterfaceFactory
     */
    private $directionsInterfaceFactory;

    /**
     * DirectionsManagement constructor.
     * @param DirectionsInterfaceFactory $directionsInterfaceFactory
     */
    public function __construct(
        DirectionsInterfaceFactory $directionsInterfaceFactory
    ) {
        $this->directionsInterfaceFactory = $directionsInterfaceFactory;
    }

    /**
     * @param string $key
     * @param string $value
     * @param string $sortName
     * @param string $sortType
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface[]
     */
    public function getDirections($key, $value, $sortName, $sortType)
    {
        $flag = 0;
        if ($key == $sortName) {
            $flag = 1;
        }
        $directions = [];
        /** @var \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptions\DirectionsInterface $direction */
        $direction = $this->directionsInterfaceFactory->create();
        $direction->setDirection("asc")
            ->setLabel($value . " (asc)")
            ->setSelected(($flag && $sortType == "asc") ? true : false);
        $directions[] = $direction;
        $direction = $this->directionsInterfaceFactory->create();
        $direction->setDirection("desc")
            ->setLabel($value . " (desc)")
            ->setSelected(($flag && $sortType == "desc") ? true : false);
        $directions[] = $direction;
        return $directions;
    }
}
