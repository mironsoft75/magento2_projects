<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Data\Form;

interface ResponseInterface
{
    const ID = "id";
    const FORM_ID = "form_id";
    const CUSTOMER_EMAIL = "customer_email";
    const CUSTOMER_IP = "customer_ip";
    const CREATED_AT = "created_at";
    const FIELDS = "response_fields";

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
     * @return int
     */
    public function getFormId();

    /**
     * @param int $formId
     * @return $this
     */
    public function setFormId($formId);

    /**
     * @return string
     */
    public function getCustomerEmail();

    /**
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail);

    /**
     * @return string
     */
    public function getCustomerIp();

    /**
     * @param string $customerIp
     * @return $this
     */
    public function setCustomerIp($customerIp);

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
     * @return \Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface[]
     */
    public function getFields();

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface[] $fields
     * @return $this
     */
    public function setFields($fields);

}