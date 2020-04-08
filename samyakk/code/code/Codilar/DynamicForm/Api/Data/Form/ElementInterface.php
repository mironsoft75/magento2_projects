<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Data\Form;

use Magento\Framework\Api\CustomAttributesDataInterface;

interface ElementInterface extends CustomAttributesDataInterface
{
    const ID = "id";
    const IDENTIFIER = "identifier";
    const LABEL = "label";
    const NAME = "name";
    const TYPE = "type";
    const CLASS_NAME = "class";
    const STORE_VIEWS = "store_views";
    const OPTIONS = "options";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const VALIDATION = "validation";
    const CUSTOM_HTML = "custom_html";

    const TYPE_TEXT = 0;
    const TYPE_TEXTAREA = 1;
    const TYPE_PASSWORD = 2;
    const TYPE_EMAIL = 3;
    const TYPE_FILE = 4;
    const TYPE_SELECT = 5;
    const TYPE_MULTISELECT = 6;
    const TYPE_CHECKBOX = 7;
    const TYPE_RADIO = 8;
    const TYPE_DATE = 9;
    const TYPE_HIDDEN = 10;
    const TYPE_CUSTOM = 100;

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getIdentifier();

    /**
     * @param string $identifier
     * @return $this
     */
    public function setIdentifier($identifier);

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel($label);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name);

    /**
     * @return int
     */
    public function getType();

    /**
     * @param int $type
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @param string $className
     * @return $this
     */
    public function setClassName($className);

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface[]
     */
    public function getOptions();

    /**
     * @return string
     */
    public function getStoreViews();

    /**
     * @param string $storeViews
     * @return $this
     */
    public function setStoreViews($storeViews);

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface[] $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * @return string
     */
    public function getCreatedAt();

    /**
     * @param string $createdAt
     * @return $this
     */
    public function setCreatedAt($createdAt);

    /**
     * @return string
     */
    public function getUpdatedAt();

    /**
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt);

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementExtensionInterface
     * @return $this
     */
    public function setExtensionAttributes(\Codilar\DynamicForm\Api\Data\Form\ElementExtensionInterface $extensionAttributes);

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface[]
     */
    public function getValidation();

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface[] $validations
     * @return $this
     */
    public function setValidation($validations);

    /**
     * @return string
     */
    public function getCustomHtml();

    /**
     * @param string $customHtml
     * @return $this
     */
    public function setCustomHtml($customHtml);
}