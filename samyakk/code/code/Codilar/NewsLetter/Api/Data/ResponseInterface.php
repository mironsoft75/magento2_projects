<?php
/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\NewsLetter\Api\Data;


interface ResponseInterface
{
    /**
     * @return boolean
     */
    public function getStatus();

    /**
     * @param boolean $status
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function setStatus($status);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function setMessage($message);
}