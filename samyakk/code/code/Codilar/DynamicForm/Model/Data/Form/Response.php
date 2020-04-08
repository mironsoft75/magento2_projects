<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Data\Form;


use Codilar\DynamicForm\Api\Data\Form\ResponseInterface;
use Magento\Framework\Api\AbstractSimpleObject;

class Response extends AbstractSimpleObject implements ResponseInterface
{

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
    public function getFormId()
    {
        return $this->_get(self::FORM_ID);
    }

    /**
     * @param int $formId
     * @return $this
     */
    public function setFormId($formId)
    {
        return $this->setData(self::FORM_ID, $formId);
    }

    /**
     * @return string
     */
    public function getCustomerEmail()
    {
        return $this->_get(self::CUSTOMER_EMAIL);
    }

    /**
     * @param string $customerEmail
     * @return $this
     */
    public function setCustomerEmail($customerEmail)
    {
        return $this->setData(self::CUSTOMER_EMAIL, $customerEmail);
    }

    /**
     * @return string
     */
    public function getCustomerIp()
    {
        return $this->_get(self::CUSTOMER_IP);
    }

    /**
     * @param string $customerIp
     * @return $this
     */
    public function setCustomerIp($customerIp)
    {
        return $this->setData(self::CUSTOMER_IP, $customerIp);
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
     * @return \Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface[]
     */
    public function getFields()
    {
        return $this->_get(self::FIELDS);
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface[] $fields
     * @return $this
     */
    public function setFields($fields)
    {
        return $this->setData(self::FIELDS, $fields);
    }
}