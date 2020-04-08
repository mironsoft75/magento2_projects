<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\HomePage\Api;

interface HomepageManagementInterface
{
    /**
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     */
    public function getHomepageData();
}
