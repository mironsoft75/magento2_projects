<?php

namespace Codilar\CategoryApi\Model\Data\CategoryPage\Details;

use Codilar\CategoryApi\Api\Data\CategoryPage\Details\ImagesInterface;
use Magento\Framework\DataObject;

class Images extends DataObject implements ImagesInterface
{

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->getData('role');
    }

    /**
     * @param string $role
     * @return $this
     */
    public function setRole($role)
    {
        return  $this->setData('role', $role);
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->getData('image');
    }

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image)
    {
        return $this->setData('image', $image);
    }
}
