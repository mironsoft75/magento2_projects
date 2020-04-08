<?php

namespace Codilar\Directory\Model\Data;

use Codilar\Directory\Api\Data\RegionInterface;
use Magento\Framework\DataObject;

class Region extends DataObject implements RegionInterface
{

    /**
     * @return int
     */
    public function getRegionId()
    {
        return $this->getData('region_id');
    }

    /**
     * @param int $regionId
     * @return $this
     */
    public function setRegionId($regionId)
    {
        return $this->setData('region_id', $regionId);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->getData('label');
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        return $this->setData('label', $label);
    }
}
