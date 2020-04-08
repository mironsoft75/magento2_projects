<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Api\Product;


interface CustomAttributesManagementInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Codilar\Product\Api\Data\Product\AttributesDataInterface[]
     */
    public function getCustomAttributes($product);
}