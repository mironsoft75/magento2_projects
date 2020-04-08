<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */


namespace Codilar\Logo\Api\Data;



interface LogoInterface
{
    /**
     * @return string
     */
    public function getLogoSrc();

    /**
     * @return string
     */
    public function getLogoAlt();

    /**
     * @return int
     */
    public function getLogoHeight();

    /**
     * @return int
     */
    public function getLogoWidth();
}
