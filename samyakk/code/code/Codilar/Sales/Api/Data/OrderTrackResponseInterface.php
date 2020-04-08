<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Sales\Api\Data;


use Codilar\Api\Api\Data\Repositories\AbstractResponseDataInterface;

interface OrderTrackResponseInterface extends AbstractResponseDataInterface
{
    /**
     * @return string[]
     */
    public function getAllStates();

    /**
     * @param string[] $states
     * @return $this
     */
    public function setAllStates($states);
}