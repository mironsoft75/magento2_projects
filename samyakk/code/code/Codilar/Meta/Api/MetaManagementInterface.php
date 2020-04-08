<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Api;


use Codilar\Meta\Api\Data\MetaData\MetaInterface;

interface MetaManagementInterface
{
    /**
     * @param string $name
     * @param string $content
     * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface
     */
    public function getMetaData($name, $content);
}