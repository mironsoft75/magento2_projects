<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Data\Form;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Model\Config\Source\Form\ElementType;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\Api\AttributeValueFactory;

class Element extends AbstractExtensibleObject implements ElementInterface
{
    /**
     * @var ElementType
     */
    private $elementType;

    /**
     * Element constructor.
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $attributeValueFactory
     * @param ElementType $elementType
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $attributeValueFactory,
        ElementType $elementType,
        array $data = []
    )
    {
        parent::__construct($extensionFactory, $attributeValueFactory, $data);
        $this->elementType = $elementType;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->_get(self::ID);
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        return $this->setData(self::ID, $id);
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->_get(self::IDENTIFIER);
    }

    /**
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        return $this->setData(self::IDENTIFIER, $identifier);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_get(self::LABEL);
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label)
    {
        return $this->setData(self::LABEL, $label);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_get(self::NAME);
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        return $this->setData(self::NAME, $name);
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->_get(self::TYPE);
    }

    /**
     * @param int $type
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setType($type)
    {
        $allowedTypes = array_keys($this->elementType->toArray());
        if (!in_array($type, $allowedTypes)) {
            throw \Magento\Framework\Exception\NoSuchEntityException::singleField("Type", $type);
        }
        return $this->setData(self::TYPE, $type);
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->_get(self::CLASS_NAME);
    }

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className)
    {
        return $this->setData(self::CLASS_NAME, $className);
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface[]
     */
    public function getOptions()
    {
        return $this->_get(self::OPTIONS);
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface[] $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->setData(self::OPTIONS, []);
        if ($options) {
            foreach ($options as $option) {
                $this->addOption($option);
            }
        }
        return $this;
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface $option
     * @return Element
     */
    protected function addOption(\Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface $option)
    {
        $options = $this->_get(self::OPTIONS);
        $options[] = $option;
        return $this->setData(self::OPTIONS, $options);
    }

    /**
     * @return string
     */
    public function getStoreViews()
    {
        return $this->_get(self::STORE_VIEWS);
    }

    /**
     * @param string $storeViews
     * @return $this
     */
    public function setStoreViews($storeViews)
    {
        return $this->setData(self::STORE_VIEWS, $storeViews);
    }

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->_get(self::CREATED_AT);
    }

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->_get(self::UPDATED_AT);
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementExtensionInterface
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementExtensionInterface
     * @return $this
     */
    public function setExtensionAttributes(\Codilar\DynamicForm\Api\Data\Form\ElementExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface[]
     */
    public function getValidation()
    {
        return $this->_get(self::VALIDATION);
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface[] $validations
     * @return $this
     */
    public function setValidation($validations)
    {
        $this->setData(self::VALIDATION, []);
        if ($validations) {
            foreach ($validations as $validation) {
                $this->addValidationOption($validation);
            }
        }
        return $this;
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface $validation
     * @return Element
     */
    protected function addValidationOption(\Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface $validation)
    {
        $validations = $this->_get(self::VALIDATION);
        $validations[] = $validation;
        return $this->setData(self::VALIDATION, $validations);
    }

    /**
     * @return string
     */
    public function getCustomHtml()
    {
        return $this->_get(self::CUSTOM_HTML);
    }

    /**
     * @param string $customHtml
     * @return $this
     */
    public function setCustomHtml($customHtml)
    {
        return $this->setData(self::CUSTOM_HTML, $customHtml);
    }
}