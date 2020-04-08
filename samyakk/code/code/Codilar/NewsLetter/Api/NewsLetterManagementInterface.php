<?php
/**
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\NewsLetter\Api;


interface NewsLetterManagementInterface
{
    /**
     * @param string $email
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function subscribe($email);

    /**
     * @return \Codilar\NewsLetter\Api\Data\ResponseInterface
     */
    public function unsubscribe();
}