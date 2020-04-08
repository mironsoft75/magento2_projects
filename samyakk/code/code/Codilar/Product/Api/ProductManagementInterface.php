<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Api;


interface ProductManagementInterface
{
    /**
     * @param string $urlKey
     * @return \Codilar\Product\Api\Data\ProductInterface
     */
    public function getProductByUrlKey($urlKey);
}