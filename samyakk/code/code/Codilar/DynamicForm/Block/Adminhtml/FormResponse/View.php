<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\FormResponse;

use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface;
use Magento\Backend\Block\Template;
use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;


class View extends Template
{
    /**
     * @var ResponseRepositoryInterface
     */
    private $responseRepository;
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;

    /**
     * View constructor.
     * @param Template\Context $context
     * @param ResponseRepositoryInterface $responseRepository
     * @param ElementRepositoryInterface $elementRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ResponseRepositoryInterface $responseRepository,
        ElementRepositoryInterface $elementRepository,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->responseRepository = $responseRepository;
        $this->elementRepository = $elementRepository;
    }

    public function getFormResponse() {
        $formId = $this->getRequest()->getParam('id');
        $formData = $this->responseRepository->load($formId);
        return $formData;
    }

    public function renderValue(FieldInterface $field)
    {
        switch ($field->getType()) {
            case ElementInterface::TYPE_FILE:
                $value = '<a href="' . $this->getUrl('*/formresponse_view/file', ['id' => $field->getId()]) . '" target="_blank">' . __("View File") . '</a>';
                break;
            default:
                $value = $field->getValue();
        }
        return $value;
    }

    public function getUpdateAction()
    {
        return $this->getUrl("dynamicform/formresponse/save");
    }

    public function getResponseId()
    {
        return $this->getRequest()->getParam("id");
    }

}