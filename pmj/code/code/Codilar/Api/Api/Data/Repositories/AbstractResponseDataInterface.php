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
     * @return bool
     */
    public function getStatus();

    /**
     * @return string
     */
    public function getMessage();
}