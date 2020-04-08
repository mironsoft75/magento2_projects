<?php

namespace Codilar\Product\Api\Data\Product;

interface SpecificationGroupInterface
{
    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getAttributes();

    /**
     * @param \Codilar\Product\Api\Data\Product\AttributesDataInterface[] $attributes
     * @return $this
     */
    public function setAttributes($attributes);
}
