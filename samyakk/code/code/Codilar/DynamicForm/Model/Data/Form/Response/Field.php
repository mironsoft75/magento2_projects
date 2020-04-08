<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Data\Form\Response;


use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface;
use Codilar\DynamicForm\Model\Config\Source\Form\ElementType;
use Magento\Framework\Api\AbstractSimpleObject;

class Field extends AbstractSimpleObject implements FieldInterface
{
    /**
     * @var ElementType
     */
    private $elementType;

    /**
     * Field constructor.
     * @param ElementType $elementType
     * @param array $data
     */
    public function __construct(
        ElementType $elementType,
        array $data = []
    )
    {
        parent::__construct($data);
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
     * @return int
     */
    public function getFormResponseId()
    {
        return $this->_get(self::FORM_RESPONSE_ID);
    }

    /**
     * @param int $formResponseId
     * @return $this
     */
    public function setFormResponseId($formResponseId)
    {
        return $this->setData(self::FORM_RESPONSE_ID, $formResponseId);
    }

    /**
     * @return string
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
     * @return string
     */
    public function getValue()
    {
        return $this->_get(self::VALUE);
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        return $this->setData(self::VALUE, $value);
    }
}