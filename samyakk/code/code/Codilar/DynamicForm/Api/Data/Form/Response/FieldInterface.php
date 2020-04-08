<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Data\Form\Response;

interface FieldInterface
{
    const ID = "id";
    const FORM_RESPONSE_ID = "form_response_id";
    const TYPE = "type";
    const NAME = "name";
    const VALUE = "value";

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     * @return FieldInterface
     */
    public function setId($id);

    /**
     * @return int
     */
    public function getFormResponseId();

    /**
     * @param int $formResponseId
     * @return FieldInterface
     */
    public function setFormResponseId($formResponseId);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param int $type
     * @return FieldInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     * @return FieldInterface
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getValue();

    /**
     * @param string $value
     * @return FieldInterface
     */
    public function setValue($value);
}