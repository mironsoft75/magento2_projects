<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Pwa\Api\Data;


interface PwaInterface
{
    const ENTITY_ID = 'entity_id';
    const REQUEST_URL = 'request_url';
    const REDIRECT_URL = 'redirect_url';

    /**
     * @return int
     */
    public function getEntityId();

    /**
     * @param int $entityId
     * @return $this
     */
    public function setEntityId($entityId);

    /**
     * @return string
     */
    public function getRequestUrl();

    /**
     * @param string $reuestUrl
     * @return $this
     */
    public function setRequestUrl($reuestUrl);

    /**
     * @return string
     */
    public function getRedirectUrl();

    /**
     * @param string $redirectUrl
     * @return $this
     */
    public function setRedirectUrl($redirectUrl);
}