<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Api\Product\Configurations;


interface OptionsManagementInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @param \Magento\Catalog\Api\Data\ProductInterface $childProduct
     * @return \Codilar\Product\Api\Data\Product\Configurations\OptionsInterface[]
     */
    public function getOptions($product, $childProduct);
}