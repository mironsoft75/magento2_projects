<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Api\Data;


interface PaymentMethodOptionInterface
{
    /**
     * @return string
     */
    public function getCode();

    /**
     * @param string $code
     * @return $this
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return boolean
     */
    public function getIsOnline();

    /**
     * @param boolean $isOnline
     * @return $this
     */
    public function setIsOnline($isOnline);

    /**
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl);

    /**
     * @return string
     */
    public function getRedirectUrl();

    /**
     * @param string $instructions
     * @return $this
     */
    public function setInstructions($instructions);

    /**
     * @return string
     */
    public function getInstructions();

    /**
     * @param string $csrfToken
     * @return mixed
     */
    public function setCsrfToken($csrfToken);

    /**
     * @return mixed
     */
    public function getCsrfToken();
}