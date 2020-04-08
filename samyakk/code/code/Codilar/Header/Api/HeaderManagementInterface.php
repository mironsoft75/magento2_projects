<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Header\Api;

Interface HeaderManagementInterface
{
    /**
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function getHeaderData();
}
