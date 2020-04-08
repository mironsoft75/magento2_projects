<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Api\Product\OptionsValues;


interface ValuesManagementInterface
{
    /**
     * @param $options
     * @return \Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface[]
     */
    public function getOptionValues($options);
}