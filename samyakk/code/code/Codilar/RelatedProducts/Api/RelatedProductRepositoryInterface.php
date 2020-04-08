<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 22/7/19
 * Time: 4:23 PM
 */

namespace Codilar\RelatedProducts\Api;

interface RelatedProductRepositoryInterface
{

    /**
     * @param int $id
     * @return \Codilar\RelatedProducts\Api\Data\RelatedProductInterface
     */
    public function getRelatedProducts($id);
}
