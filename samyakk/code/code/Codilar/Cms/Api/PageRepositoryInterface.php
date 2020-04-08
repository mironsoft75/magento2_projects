<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/4/19
 * Time: 12:41 PM
 */

namespace Codilar\Cms\Api;


interface PageRepositoryInterface
{
    /**
     * @param string $identifier
     * @return \Codilar\Cms\Api\Data\PageResponseInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIdentifier($identifier);
}