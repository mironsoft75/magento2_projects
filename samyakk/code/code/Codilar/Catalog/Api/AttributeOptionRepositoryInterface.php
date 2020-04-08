<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Api;


interface AttributeOptionRepositoryInterface
{
    /**
     * @param string[] $attributes
     * @param string $entityType
     * @return \Codilar\Catalog\Api\Data\AttributeInterface[]
     */
    public function getOptionValues($attributes, $entityType = \Magento\Catalog\Model\Product::ENTITY);
}