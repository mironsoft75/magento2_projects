<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Data;


use Codilar\DynamicForm\Api\Data\FormInterface;
use Codilar\DynamicForm\Model\Config\Source\FormPlacement;
use Magento\Framework\Api\AbstractExtensibleObject;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\AttributeValueFactory;

class Form extends AbstractExtensibleObject implements FormInterface
{
    /**
     * @var FormPlacement
     */
    private $formPlacement;

    /**
     * Form constructor.
     * @param ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $attributeValueFactory
     * @param FormPlacement $formPlacement
     * @param array $data
     */
    public function __construct(
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $attributeValueFactory,
        FormPlacement $formPlacement,
        array $data = []
    )
    {
        parent::__construct($extensionFactory, $attributeValueFactory, $data);
        $this->formPlacement = $formPlacement;
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
    public function getTitle()
    {
        return $this->_get(self::TITLE);
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->_get(self::CONTENT);
    }

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content)
    {
        return $this->setData(self::CONTENT, $content);
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface[]
     */
    public function getFormElements()
    {
        return $this->_get(self::FORM_ELEMENTS);
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface[] $formElements
     * @return $this
     */
    public function setFormElements($formElements)
    {
        $this->setData(self::FORM_ELEMENTS, []);
        if ($formElements) {
            foreach ($formElements as $formElement) {
                $this->addFormElement($formElement);
            }
        }
        return $this;
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement
     * @return Form
     */
    protected function addFormElement(\Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement)
    {
        $formElements = $this->_get(self::FORM_ELEMENTS);
        $formElements[] = $formElement;
        return $this->setData(self::FORM_ELEMENTS, $formElements);
    }

    /**
     * @return int
     */
    public function getFormPlacement()
    {
        return $this->_get(self::FORM_PLACEMENT);
    }

    /**
     * @param int $formPlacement
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setFormPlacement($formPlacement)
    {
        $allowedFormPlacements = array_keys($this->formPlacement->toArray());
        if (!in_array($formPlacement, $allowedFormPlacements)) {
            throw \Magento\Framework\Exception\NoSuchEntityException::singleField("Form Placement", $formPlacement);
        }
        return $this->setData(self::FORM_PLACEMENT, $formPlacement);
    }

    /**
     * @return boolean
     */
    public function getIsActive()
    {
        return (boolean)$this->_get(self::IS_ACTIVE);
    }

    /**
     * @param boolean $isActive
     * @return $this
     */
    public function setIsActive($isActive)
    {
        return $this->setData(self::IS_ACTIVE, $isActive ? 1 : 0);
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
     * @param string $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\FormExtensionInterface
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\FormExtensionInterface
     * @return $this
     */
    public function setExtensionAttributes(\Codilar\DynamicForm\Api\Data\FormExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * @return string
     */
    public function getResponseMessage()
    {
        return $this->_get(self::RESPONSE_MESSAGE);
    }

    /**
     * @param string $responseMessage
     * @return $this
     */
    public function setResponseMessage($responseMessage)
    {
        return $this->setData(self::RESPONSE_MESSAGE, $responseMessage);
    }

    /**
     * @return string
     */
    public function getFormCss()
    {
        return $this->_get(self::FORM_CSS);
    }

    /**
     * @param string $css
     * @return $this
     */
    public function setFormCss($css)
    {
        return $this->setData(self::FORM_CSS, $css);
    }

    /**
     * @return string
     */
    public function getEmailTemplate()
    {
        return $this->_get(self::EMAIL_TEMPLATE);
    }

    /**
     * @param string $emailTemplate
     * @return $this
     */
    public function setEmailTemplate($emailTemplate)
    {
        return $this->setData(self::EMAIL_TEMPLATE, $emailTemplate);
    }

    /**
     * @return string
     */
    public function getEmailSender()
    {
        return $this->_get(self::EMAIL_SENDER);
    }

    /**
     * @param string $emailSender
     * @return $this
     */
    public function setEmailSender($emailSender)
    {
        return $this->setData(self::EMAIL_SENDER, $emailSender);
    }

    /**
     * @return string
     */
    public function getSendEmailCopyTo()
    {
        return $this->_get(self::SEND_EMAIL_COPY_TO);
    }

    /**
     * @param string $sendEmailCopyTo
     * @return $this
     */
    public function setSendEmailCopyTo($sendEmailCopyTo)
    {
        return $this->setData(self::SEND_EMAIL_COPY_TO, $sendEmailCopyTo);
    }
}