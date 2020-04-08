<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\Form;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Api\Data\FormInterface;
use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class Save extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form";
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param FormRepositoryInterface $formRepository
     * @param DataPersistorInterface $dataPersistor
     * @param ElementRepositoryInterface $elementRepository
     */
    public function __construct(
        Action\Context $context,
        FormRepositoryInterface $formRepository,
        DataPersistorInterface $dataPersistor,
        ElementRepositoryInterface $elementRepository
    )
    {
        parent::__construct($context);
        $this->formRepository = $formRepository;
        $this->dataPersistor = $dataPersistor;
        $this->elementRepository = $elementRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        try {
            if (!array_key_exists('id', $data)) {
                throw new NoSuchEntityException(__("The form no longer exists"));
            }
            $this->dataPersistor->set('dynamicform_form', $data);
            if ($data['id']) {
                try {
                    $form = $this->formRepository->load($data['id']);
                } catch (NoSuchEntityException $e) {
                    throw new LocalizedException(__("The form no longer exists"));
                }
            } else {
                $form = $this->formRepository->create();
            }
            $form->setTitle($data['title'])
                ->setIdentifier($data['identifier'])
                ->setIsActive($data['is_active'])
                ->setFormPlacement($data['form_placement'])
                ->setStoreViews(implode(',', $data['store_views']))
                ->setContent($data['content'])
                ->setResponseMessage($data['response_message'])
                ->setFormCss($data['form_css'])
                ->setEmailTemplate($data['email_template'])
                ->setEmailSender($data['email_sender'])
                ->setSendEmailCopyTo($data['send_email_copy_to'])
                ->setFormElements($this->getFormElements(explode(',', $data['form_element_ids'])));
            try {
                $form = $this->formRepository->save($form);
                $this->dataPersistor->clear('dynamicform_form');
            } catch (CouldNotSaveException $couldNotSaveException) {
                $this->messageManager->addErrorMessage($couldNotSaveException->getMessage());
                $backUrl = $this->getUrl("*/*/edit", ['id' => $form->getId()]);
                throw new \Exception("Go back");
            }
            $this->messageManager->addSuccessMessage(__("Form saved successfully"));
            if ($data['back'] === "continue") {
                $backUrl = $this->getUrl("*/*/edit", ['id' => $form->getId()]);
            } else if ($data['back'] === "duplicate") {
                $newForm = $this->duplicateForm($form);
                $this->messageManager->addSuccessMessage(__("Form duplicated successfully"));
                $backUrl = $this->getUrl("*/*/edit", ['id' => $newForm->getId()]);
            } else {
                $backUrl = $this->getUrl('*/*');
            }
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
            $backUrl = $this->getUrl("*/*");
        } catch (\Exception $exception) {}
        return $this->resultRedirectFactory->create()->setUrl($backUrl);
    }

    /**
     * @param FormInterface $form
     * @return FormInterface
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function duplicateForm(FormInterface $form) {
        $newForm = $this->formRepository->create();
        $newForm->setTitle($form->getTitle())
            ->setIdentifier($form->getIdentifier() . '-' . 'duplicated')
            ->setIsActive($form->getIsActive())
            ->setFormPlacement($form->getFormPlacement())
            ->setStoreViews($form->getStoreViews())
            ->setContent($form->getContent())
            ->setResponseMessage($form->getResponseMessage())
            ->setFormCss($form->getFormCss())
            ->setFormElements($form->getFormElements());
        return $this->formRepository->save($newForm);
    }

    /**
     * @param int[] $ids
     * @return ElementInterface[]
     * @throws LocalizedException
     */
    protected function getFormElements($ids) {
        $formElements = [];
        foreach ($ids as $id) {
            try {
                $formElements[] = $this->elementRepository->load($id);
            } catch (NoSuchEntityException $e) {
                throw new LocalizedException(__("Form element with ID %1 does not exist", $id));
            }
        }
        return $formElements;
    }
}