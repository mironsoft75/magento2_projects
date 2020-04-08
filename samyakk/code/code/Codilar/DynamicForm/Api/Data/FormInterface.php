<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Api\Data;

use Magento\Framework\Api\CustomAttributesDataInterface;

interface FormInterface extends CustomAttributesDataInterface
{

    const ID = "id";
    const IDENTIFIER = "identifier";
    const TITLE = "title";
    const CONTENT = "content";
    const FORM_ELEMENTS = "form_elements";
    const FORM_PLACEMENT = "form_placement";
    const IS_ACTIVE = "is_active";
    const STORE_VIEWS = "store_views";
    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const RESPONSE_MESSAGE = "response_message";
    const FORM_CSS = "form_css";
    const EMAIL_TEMPLATE = "email_template";
    const EMAIL_SENDER = "email_sender";
    const SEND_EMAIL_COPY_TO = "send_email_copy_to";

    const FORM_PLACEMENT_TOP = 0;
    const FORM_PLACEMENT_BOTTOM = 1;
    const FORM_PLACEMENT_LEFT = 2;
    const FORM_PLACEMENT_RIGHT = 3;
    const FORM_PLACEMENT_WRAP = 4;

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
    public function getTitle();

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     * @return $this
     */
    public function setContent($content);

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface[]
     */
    public function getFormElements();

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface[] $formElements
     * @return $this
     */

    public function setFormElements($formElements);

    /**
     * @return int
     */
    public function getFormPlacement();

    /**
     * @param int $formPlacement
     * @return $this
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setFormPlacement($formPlacement);

    /**
     * @return boolean
     */
    public function getIsActive();

    /**
     * @param boolean $isActive
     * @return $this
     */
    public function setIsActive($isActive);

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
     * @return string
     */
    public function getResponseMessage();

    /**
     * @param string $responseMessage
     * @return $this
     */
    public function setResponseMessage($responseMessage);

    /**
     * @return \Codilar\DynamicForm\Api\Data\FormExtensionInterface
     */
    public function getExtensionAttributes();

    /**
     * @param \Codilar\DynamicForm\Api\Data\FormExtensionInterface
     * @return $this
     */
    public function setExtensionAttributes(\Codilar\DynamicForm\Api\Data\FormExtensionInterface $extensionAttributes);

    /**
     * @return string
     */
    public function getFormCss();

    /**
     * @param string $css
     * @return $this
     */
    public function setFormCss($css);

    /**
     * @return string
     */
    public function getEmailTemplate();

    /**
     * @param string $emailTemplate
     * @return $this
     */
    public function setEmailTemplate($emailTemplate);

    /**
     * @return string
     */
    public function getEmailSender();

    /**
     * @param string $emailSender
     * @return $this
     */
    public function setEmailSender($emailSender);

    /**
     * @return string
     */
    public function getSendEmailCopyTo();

    /**
     * @param string $sendEmailCopyTo
     * @return $this
     */
    public function setSendEmailCopyTo($sendEmailCopyTo);
}