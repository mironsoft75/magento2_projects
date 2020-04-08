<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block;


use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Codilar\DynamicForm\Helper\VariableFieldRenderer;
use Magento\Cms\Model\Template\FilterProvider;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Form extends Template
{
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var FilterProvider
     */
    private $filterProvider;
    /**
     * @var VariableFieldRenderer
     */
    private $variableFieldRenderer;

    /**
     * Form constructor.
     * @param Template\Context $context
     * @param FormRepositoryInterface $formRepository
     * @param FilterProvider $filterProvider
     * @param VariableFieldRenderer $variableFieldRenderer
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        FormRepositoryInterface $formRepository,
        FilterProvider $filterProvider,
        VariableFieldRenderer $variableFieldRenderer,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->formRepository = $formRepository;
        $this->filterProvider = $filterProvider;
        $this->variableFieldRenderer = $variableFieldRenderer;
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function getForm()
    {
        try {
            return $this->formRepository->load($this->getRequest()->getParam('id'));
        } catch (NoSuchEntityException $e) {
            return $this->formRepository->create();
        }
    }

    /**
     * @return string
     */
    public function getFormContent()
    {
        try {
            $form = $this->getForm();
            $formContent = $this->filterProvider->getBlockFilter()->filter($form->getContent());
            if ($form->getFormPlacement() == \Codilar\DynamicForm\Api\Data\FormInterface::FORM_PLACEMENT_WRAP) {
                $formContent = $this->variableFieldRenderer->render($formContent, ['form' => $this->getChildHtml('dynamicform.form.renderer')]);
            }
            return $formContent;
        } catch (\Exception $e) {
            return '';
        }
    }
}