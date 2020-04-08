<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Controller\Adminhtml\Element;


use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Magento\Backend\App\Action;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\DynamicForm\Api\Data\Form\Element\OptionInterfaceFactory as FormElementOptionInterfaceFactory;
use Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterfaceFactory as ValidationOptionInterfaceFactory;

class Save extends Action
{
    const ADMIN_RESOURCE = "Codilar_DynamicForm::form_element";
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;
    /**
     * @var FormElementOptionInterfaceFactory
     */
    private $formElementOptionInterfaceFactory;
    /**
     * @var ValidationOptionInterfaceFactory
     */
    private $validationOptionsInterfaceFactory;

    /**
     * Save constructor.
     * @param Action\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param ElementRepositoryInterface $elementRepository
     * @param FormElementOptionInterfaceFactory $formElementOptionInterfaceFactory
     * @param ValidationOptionInterfaceFactory $validationOptionsInterfaceFactory
     */
    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        ElementRepositoryInterface $elementRepository,
        FormElementOptionInterfaceFactory $formElementOptionInterfaceFactory,
        ValidationOptionInterfaceFactory $validationOptionsInterfaceFactory
    )
    {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->elementRepository = $elementRepository;
        $this->formElementOptionInterfaceFactory = $formElementOptionInterfaceFactory;
        $this->validationOptionsInterfaceFactory = $validationOptionsInterfaceFactory;
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
                throw new NoSuchEntityException(__("The form element no longer exists"));
            }
            $this->dataPersistor->set('dynamicform_form_element', $data);
            if ($data['id']) {
                try {
                    $formElement = $this->elementRepository->load($data['id']);
                } catch (NoSuchEntityException $e) {
                    throw new LocalizedException(__("The form element no longer exists"));
                }
            } else {
                $formElement = $this->elementRepository->create();
            }

            $options = [];
            $optionsJson = \json_decode($data['options_json'], true);
            if ($optionsJson) {
                foreach ($optionsJson as $optionItem) {
                    $option = $this->formElementOptionInterfaceFactory->create();
                    $option->setLabel($optionItem['label'])
                        ->setValue($optionItem['value']);
                    $options[] = $option;
                }
            }

            $validationOptions = [];
            $validationOptionsJson = \json_decode($data['validation_json'], true);
            if ($validationOptionsJson) {
                foreach ($validationOptionsJson as $validationOptionItem) {
                    $valiationOption = $this->validationOptionsInterfaceFactory->create();
                    $valiationOption->setLabel($validationOptionItem['label'])
                        ->setValue($validationOptionItem['value']);
                    $validationOptions[] = $valiationOption;
                }
            }

            $formElement->setIdentifier($data['identifier'])
                ->setLabel($data['label'])
                ->setClassName($data['class_name'])
                ->setName($data['name'])
                ->setType($data['type'])
                ->setOptions($options)
                ->setCustomHtml($data['custom_html'])
                ->setValidation($validationOptions)
                ->setStoreViews(implode(',', $data['store_views']));

            try {
                $formElement = $this->elementRepository->save($formElement);
                $this->dataPersistor->clear('dynamicform_form_element');
            } catch (CouldNotSaveException $couldNotSaveException) {
                $this->messageManager->addErrorMessage($couldNotSaveException->getMessage());
                $backUrl = $this->getUrl("*/*/edit", ['id' => $formElement->getId()]);
                throw new \Exception("Go back");
            }
            $this->messageManager->addSuccessMessage(__("Form element saved successfully"));
            if ($data['back'] === "continue") {
                $backUrl = $this->getUrl("*/*/edit", ['id' => $formElement->getId()]);
            } else if ($data['back'] === "duplicate") {
                $newFormElement = $this->duplicateFormElement($formElement);
                $this->messageManager->addSuccessMessage(__("Form element duplicated successfully"));
                $backUrl = $this->getUrl("*/*/edit", ['id' => $newFormElement->getId()]);
            } else {
                $backUrl = $this->getUrl('*/*');
            }
        } catch (LocalizedException $localizedException) {
            $this->messageManager->addErrorMessage($localizedException->getMessage());
            $backUrl = $this->getUrl("*/*");
        } catch (\Exception $exception) {
            echo $exception->getMessage();
        }
        return $this->resultRedirectFactory->create()->setUrl($backUrl);
    }

    /**
     * @param ElementInterface $formElement
     * @return ElementInterface
     * @throws NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    protected function duplicateFormElement(ElementInterface $formElement) {
        $newFormElement = $this->elementRepository->create();
        $newFormElement->setIdentifier($formElement->getIdentifier() . '-' . 'duplicated')
            ->setLabel($formElement->getLabel())
            ->setClassName($formElement->getClassName())
            ->setName($formElement->getName())
            ->setType($formElement->getType())
            ->setOptions($formElement->getOptions())
            ->setStoreViews($formElement->getStoreViews());
        return $this->elementRepository->save($newFormElement);
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