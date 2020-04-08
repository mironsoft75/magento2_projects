<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Api\Product\CustomOptions;


interface ValuesManagementInterface
{
    /**
     * @param $customOptionValue
     * @return \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface[]
     */
    public function getCustomOptionsValues($customOptionValue);
}