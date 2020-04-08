<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model\Product\CustomOptions;


use Codilar\Product\Api\Data\Product\OptionValues\ValuesInterface;
use Codilar\Product\Api\Data\Product\OptionValues\ValuesInterfaceFactory;
use Codilar\Product\Api\Product\CustomOptions\ValuesManagementInterface;

class ValuesManagement implements ValuesManagementInterface
{
    /**
     * @var ValuesInterfaceFactory
     */
    private $valuesInterfaceFactory;

    /**
     * ValuesManagement constructor.
     * @param ValuesInterfaceFactory $valuesInterfaceFactory
     */
    public function __construct(
        ValuesInterfaceFactory $valuesInterfaceFactory
    )
    {
        $this->valuesInterfaceFactory = $valuesInterfaceFactory;
    }

    /**
     * @param $customOptionValues
     * @return \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface[]
     */
    public function getCustomOptionsValues($customOptionValues)
    {
        $values = [];
        if ($customOptionValues) {
            foreach ($customOptionValues as $customOptionValue) {
                /** @var \Codilar\Product\Api\Data\Product\CustomOptions\ValuesInterface $value */
                $value = $this->valuesInterfaceFactory->create();
                $value->setId($customOptionValue->getOptionTypeId())
                    ->setLabel($customOptionValue->getTitle())
                    ->setSku($customOptionValue->getSku())
                    ->setPrice($customOptionValue->getDefaultPrice());
                $values[] = $value;
            }
        }
        return $values;
    }
}