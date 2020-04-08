<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Api\Data;


interface NotifyResponseInterface
{
    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @param boolean $status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);
}