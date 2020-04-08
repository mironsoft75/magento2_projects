<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Pool;


class CustomAttributesPool
{
    /**
     * @var array
     */
    private $customAttributes;

    /**
     * SpecificationsPool constructor.
     * @param array $customAttributes
     */
    public function __construct(
        array $customAttributes = []
    )
    {
        $this->customAttributes = $customAttributes;
    }

    /**
     * @return array
     */
    public function getCustomAttributes(): array
    {
        return $this->customAttributes;
    }
}