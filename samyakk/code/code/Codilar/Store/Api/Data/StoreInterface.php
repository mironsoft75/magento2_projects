<?php
/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Store\Api\Data;

interface StoreInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getCode();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return int|bool
     */
    public function getIsActive();

    /**
     * @return int
     */
    public function getWebsiteId();
}
