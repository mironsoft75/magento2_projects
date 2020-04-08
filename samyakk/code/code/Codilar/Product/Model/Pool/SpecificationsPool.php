<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Pool;


class SpecificationsPool
{
    /**
     * @var array
     */
    private $specifications;

    /**
     * SpecificationsPool constructor.
     * @param array $specifications
     */
    public function __construct(
        array $specifications = []
    )
    {
        $this->specifications = $specifications;
    }

    /**
     * @return array
     */
    public function getSpecifications(): array
    {
        return $this->specifications;
    }
}