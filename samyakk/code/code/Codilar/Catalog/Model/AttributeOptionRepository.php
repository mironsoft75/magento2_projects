<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Catalog\Model;


use Codilar\Catalog\Api\AttributeOptionRepositoryInterface;
use Codilar\Catalog\Api\Data\AttributeInterface;
use Codilar\Catalog\Api\Data\AttributeInterfaceFactory;
use Codilar\Catalog\Api\Data\AttributeOptionInterface;
use Codilar\Catalog\Api\Data\AttributeOptionInterfaceFactory;
use Magento\Eav\Model\Config as EavConfig;
use Magento\Framework\Exception\LocalizedException;

class AttributeOptionRepository implements AttributeOptionRepositoryInterface
{
    /**
     * @var AttributeOptionInterfaceFactory
     */
    private $attributeOptionFactory;
    /**
     * @var AttributeInterfaceFactory
     */
    private $attributeFactory;
    /**
     * @var EavConfig
     */
    private $eavConfig;

    /**
     * AttributeOptionRepository constructor.
     * @param AttributeInterfaceFactory $attributeFactory
     * @param AttributeOptionInterfaceFactory $attributeOptionFactory
     * @param EavConfig $eavConfig
     */
    public function __construct(
        AttributeInterfaceFactory $attributeFactory,
        AttributeOptionInterfaceFactory $attributeOptionFactory,
        EavConfig $eavConfig
    )
    {
        $this->attributeFactory = $attributeFactory;
        $this->attributeOptionFactory = $attributeOptionFactory;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @param string[] $attributes
     * @param string $entityType
     * @return \Codilar\Catalog\Api\Data\AttributeInterface[]
     * @throws LocalizedException
     */
    public function getOptionValues($attributes, $entityType = \Magento\Catalog\Model\Product::ENTITY)
    {
        $response = [];
        foreach ($attributes as $attribute) {
            $attribute = $this->eavConfig->getAttribute($entityType, $attribute);
            $optionResponse = [];
            if ($attribute->usesSource()) {
                try {
                    $options = $attribute->getSource()->getAllOptions();
                } catch (LocalizedException $e) {
                    $options = [];
                }
                foreach ($options as $option) {
                    $optionResponse[] = $this->getAttributeOptionInstance($option['value'], $option['label']);
                }
            }
            $response[] = $this->getAttributeInstance()
                ->setAttributeCode($attribute->getAttributeCode())
                ->setUsesSource($attribute->usesSource())
                ->setOptions($optionResponse);
        }
        return $response;
    }

    /**
     * @return AttributeInterface
     */
    protected function getAttributeInstance()
    {
        return $this->attributeFactory->create();
    }

    /**
     * @param int $id
     * @param string $label
     * @return AttributeOptionInterface
     */
    protected function getAttributeOptionInstance($id, $label)
    {
        return $this->attributeOptionFactory->create()->setOptionId($id)->setOptionLabel($label);
    }
}