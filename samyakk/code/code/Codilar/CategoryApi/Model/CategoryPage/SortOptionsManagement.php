<?php

namespace Codilar\CategoryApi\Model\CategoryPage;

use Codilar\CategoryApi\Api\CategoryPage\SortOptions\DirectionsManagementInterface;
use Codilar\CategoryApi\Api\CategoryPage\SortOptionsManagementInterface;
use Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterfaceFactory;
use Magento\Catalog\Model\Config;

class SortOptionsManagement implements SortOptionsManagementInterface
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var SortOptionsInterfaceFactory
     */
    private $sortOptionsInterfaceFactory;
    /**
     * @var DirectionsManagementInterface
     */
    private $directionsManagement;

    /**
     * SortOptionsManagement constructor.
     * @param Config $config
     * @param SortOptionsInterfaceFactory $sortOptionsInterfaceFactory
     * @param DirectionsManagementInterface $directionsManagement
     */
    public function __construct(
        Config $config,
        SortOptionsInterfaceFactory $sortOptionsInterfaceFactory,
        DirectionsManagementInterface $directionsManagement
    ) {
        $this->config = $config;
        $this->sortOptionsInterfaceFactory = $sortOptionsInterfaceFactory;
        $this->directionsManagement = $directionsManagement;
    }

    /**
     * @param string $sort
     * @return \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface[]
     */
    public function getSortData($sort)
    {
        $sortType = null;
        $sort = explode("-", $sort);
        if (isset($sort[1])) {
            $sortType = $sort[1];
        }
        $sortName = $sort[0];
        $sortOptions = $this->config->getAttributeUsedForSortByArray();
        $optionsArray = [];
        foreach ($sortOptions as $key => $value) {
            /** @var \Codilar\CategoryApi\Api\Data\CategoryPage\SortOptionsInterface $options */
            $options = $this->sortOptionsInterfaceFactory->create();
            if ($key == 'position') {
                $options->setId('trending')
                    ->setLabel('Trending')
                    ->setSelected(($sortName == 'trending') ? true : false);
            } else {
                $options->setId($key)
                    ->setLabel($value)
                    ->setSelected(($sortName == $key) ? true : false)
                    ->setDirections($this->directionsManagement->getDirections($key, $value, $sortName, $sortType));
            }
            $optionsArray[] = $options;
        }
        return $optionsArray;
    }
}
