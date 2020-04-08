<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */


namespace Codilar\Logo\Api;


interface LogoRepositoryInterface
{
    /**
     * @return \Codilar\Logo\Api\Data\LogoInterface
     */
    public function getLogo();
}
