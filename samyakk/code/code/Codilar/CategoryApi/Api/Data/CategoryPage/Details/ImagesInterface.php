<?php

namespace Codilar\CategoryApi\Api\Data\CategoryPage\Details;

interface ImagesInterface
{
    /**
     * @return string
     */
    public function getRole();

    /**
     * @param string $role
     * @return $this
     */
    public function setRole($role);

    /**
     * @return string
     */
    public function getImage();

    /**
     * @param string $image
     * @return $this
     */
    public function setImage($image);
}
