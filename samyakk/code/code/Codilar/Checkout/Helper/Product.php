<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Checkout\Helper;

use Magento\Catalog\Model\ProductOptions\Config as ProductOptionsConfig;
use Magento\Catalog\Api\Data\ProductCustomOptionInterface;

class Product
{
    /**
     * @var ProductOptionsConfig
     */
    private $productOptionsConfig;

    /**
     * Product constructor.
     * @param ProductOptionsConfig $productOptionsConfig
     */
    public function __construct(
        ProductOptionsConfig $productOptionsConfig
    )
    {
        $this->productOptionsConfig = $productOptionsConfig;
    }

    /**
     * @param ProductCustomOptionInterface $option
     * @return string
     */
    public function getProductOptionType($option)
    {
        $allOptions = $this->productOptionsConfig->getAll();
        $currentOptionType = $option->getType();
        foreach ($allOptions as $optionType => $optionTypeData) {
            if (in_array($currentOptionType, array_keys($optionTypeData['types']))) {
                return $optionType;
            }
        }
        return $currentOptionType;
    }
}