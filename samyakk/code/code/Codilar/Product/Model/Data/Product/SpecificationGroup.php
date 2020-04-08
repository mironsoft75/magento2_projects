<?php

namespace Codilar\Product\Model\Data\Product;

use Codilar\Product\Api\Data\Product\SpecificationGroupInterface;
use Magento\Framework\DataObject;

class SpecificationGroup extends DataObject implements SpecificationGroupInterface
{

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

    /**
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getAttributes()
    {
        return $this->getData('attributes');
    }

    /**
     * @param \Codilar\Product\Api\Data\Product\AttributesDataInterface[] $attributes
     * @return $this
     */
    public function setAttributes($attributes)
    {
        return $this->setData('attributes', $attributes);
    }
}
