<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 5/11/17
 * Time: 3:36 PM
 */

namespace Codilar\Api\Api\Data\Repositories;


interface AbstractResponseDataInterface
{
    /**
     * @param boolean$status
     * @return $this
     */
    public function setStatus($status);

    /**
     * @return bool
     */
    public function getStatus();

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getMessage();
}