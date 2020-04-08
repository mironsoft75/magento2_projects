<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Api\Types;


use Codilar\Meta\Api\Data\MetaDataInterface;

interface MetaDataTypeInterface
{
    /**
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     */
    public function getMetaTypeData($id);
}