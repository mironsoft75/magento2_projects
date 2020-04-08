<?php

namespace Codilar\CategoryApi\Api;

use Codilar\CategoryApi\Api\Data\CategoryPageInterface;

interface CategoryManagementInterface
{
    /**
     * @param int $id
     * @return CategoryPageInterface
     */
    public function getCategory($id);
}
