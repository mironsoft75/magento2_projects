<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model;

use Codilar\Api\Helper\Customer;
use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface;
use Codilar\DynamicForm\Api\Data\FormInterface;
use Codilar\DynamicForm\Api\Data\FormItemInterface;
use Codilar\DynamicForm\Api\Data\FormItemInterfaceFactory as FormItemFactory;
use Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface;
use Codilar\DynamicForm\Api\Form\FormSubmitResponseInterfaceFactory;
use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Codilar\DynamicForm\Api\FormManagementInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Codilar\DynamicForm\Exception\IllegalEntryException;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\LayoutFactory;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterfaceFactory;
use Codilar\DynamicForm\Helper\VariableFieldRenderer;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Codilar\Core\Helper\Product as ProductHelper;


class FormManagement implements FormManagementInterface
{
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var FilterBuilder
     */
    private $filterBuilder;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseRepositoryInterface
     */
    private $responseRepository;
    /**
     * @var RemoteAddress
     */
    private $remoteAddress;
    /**
     * @var FieldInterfaceFactory
     */
    private $fieldInterfaceFactory;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var VariableFieldRenderer
     */
    private $variableFieldRenderer;
    /**
     * @var Customer
     */
    private $customerHelper;
    /**
     * @var Filesystem
     */
    private $fileSystem;
    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;
    /**
     * @var FormSubmitResponseInterfaceFactory
     */
    private $formSubmitResponseInterfaceFactory;
    /**
     * @var ProductHelper
     */
    private $productHelper;
    /**
     * @var FormItemFactory
     */
    private $formItemFactory;

    /**
     * FormManagement constructor.
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param FilterBuilder $filterBuilder
     * @param StoreManagerInterface $storeManager
     * @param FormRepositoryInterface $formRepository
     * @param LayoutFactory $layoutFactory
     * @param RequestInterface $request
     * @param ResponseRepositoryInterface $responseRepository
     * @param RemoteAddress $remoteAddress
     * @param FieldInterfaceFactory $fieldInterfaceFactory
     * @param ManagerInterface $messageManager
     * @param VariableFieldRenderer $variableFieldRenderer
     * @param Customer $customerHelper
     * @param Filesystem $fileSystem
     * @param UploaderFactory $uploaderFactory
     * @param FormSubmitResponseInterfaceFactory $formSubmitResponseInterfaceFactory
     * @param ProductHelper $productHelper
     * @param FormItemFactory $formItemFactory
     */
    public function __construct(
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        FilterBuilder $filterBuilder,
        StoreManagerInterface $storeManager,
        FormRepositoryInterface $formRepository,
        LayoutFactory $layoutFactory,
        RequestInterface $request,
        ResponseRepositoryInterface $responseRepository,
        RemoteAddress $remoteAddress,
        FieldInterfaceFactory $fieldInterfaceFactory,
        ManagerInterface $messageManager,
        VariableFieldRenderer $variableFieldRenderer,
        Customer $customerHelper,
        Filesystem $fileSystem,
        UploaderFactory $uploaderFactory,
        FormSubmitResponseInterfaceFactory $formSubmitResponseInterfaceFactory,
        ProductHelper $productHelper,
        FormItemFactory $formItemFactory
    )
    {
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->filterBuilder = $filterBuilder;
        $this->storeManager = $storeManager;
        $this->formRepository = $formRepository;
        $this->layoutFactory = $layoutFactory;
        $this->request = $request;
        $this->responseRepository = $responseRepository;
        $this->remoteAddress = $remoteAddress;
        $this->fieldInterfaceFactory = $fieldInterfaceFactory;
        $this->messageManager = $messageManager;
        $this->variableFieldRenderer = $variableFieldRenderer;
        $this->customerHelper = $customerHelper;
        $this->fileSystem = $fileSystem;
        $this->uploaderFactory = $uploaderFactory;
        $this->formSubmitResponseInterfaceFactory = $formSubmitResponseInterfaceFactory;
        $this->productHelper = $productHelper;
        $this->formItemFactory = $formItemFactory;
    }

    /**
     * @param int $id
     * @return FormInterface
     * @throws NoSuchEntityException
     */
    public function renderById($id)
    {
        $form = $this->formRepository->load($id);
        if (!$form->getIsActive()) {
            throw NoSuchEntityException::singleField('id', $id);
        }
        return $this->render($form);
    }

    /**
     * @param string $identifier
     * @return FormInterface
     * @throws NoSuchEntityException
     */
    public function renderByIdentifier($identifier)
    {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $storeViewFilters = [
            $this->filterBuilder->setField('store_views')->setValue(\Codilar\DynamicForm\Ui\Component\Listing\Column\Source\Store::ALL_STORE_VIEWS)->setConditionType('finset')->create(),
            $this->filterBuilder->setField('store_views')->setValue($this->storeManager->getStore()->getId())->setConditionType('finset')->create()
        ];
        $searchCriteriaBuilder->addFilters($storeViewFilters);
        $searchCriteriaBuilder->addFilter('identifier', $identifier);
        $searchCriteriaBuilder->addFilter('is_active', 1);
        $formList = $this->formRepository->getList($searchCriteriaBuilder->create())->getItems();
        if (count($formList)) {
            /** @var FormInterface $form */
            $form = reset($formList);
            return $this->renderById($form->getId());
        }
        throw NoSuchEntityException::singleField("identifier", $identifier);
    }

    /**
     * @param FormInterface $form
     * @return FormInterface
     */
    protected function render(FormInterface $form)
    {
        $this->request->setParam('id', $form->getId());
        /** @var \Codilar\DynamicForm\Block\Form $formBlock */
        $formBlock = $this->layoutFactory->create()->createBlock(\Codilar\DynamicForm\Block\Form::class);
        $formBlock->setTemplate('Codilar_DynamicForm::form.phtml');
        /** @var \Codilar\DynamicForm\Block\Form\Renderer $formRendererBlock */
        $formRendererBlock = $this->layoutFactory->create()->createBlock(\Codilar\DynamicForm\Block\Form\Renderer::class);
        $formRendererBlock->setTemplate('Codilar_DynamicForm::form/renderer.phtml');
        $formBlock->addChild('dynamicform.form.renderer', $formRendererBlock);
        $content = $formBlock->toHtml();
        $form->setContent($content);
        return $form;
    }


    /**
     * @param int $id
     * @param \Codilar\DynamicForm\Api\Form\DynamicFormFieldDataInterface[] $response
     * @return \Codilar\DynamicForm\Api\Form\FormSubmitResponseInterface
     * @throws LocalizedException
     */
    public function submit($id, $response)
    {
        $result = [];
        try {
            /** @var FormSubmitResponseInterface $result */
            $result = $this->formSubmitResponseInterfaceFactory->create();
            $result->setStatus(true)->setMessage("Form has been saved successfully!");
            $form = $this->formRepository->load($id);
            $data = [];
            foreach ($response as $fieldData) {
                $data[$fieldData->getKey()] = $fieldData->getValue();
            }
            $fileData = $_FILES;
            $data = array_merge($data, $fileData);
            $response = $this->responseRepository->create();
            $customer = $this->customerHelper->getCustomerIdByToken(true);
            if ($customer) {
                $customerEmail = $customer->getEmail();
            } else {
                $customerEmail = "";
            }
            $response->setFormId($form->getId())
                ->setFields([])
                ->setCustomerEmail($customerEmail)
                ->setCustomerIp($this->remoteAddress->getRemoteAddress());
            $response = $this->responseRepository->save($response, false);
            $fields = [];
            foreach ($form->getFormElements() as $formElement) {
                if (array_key_exists($formElement->getName(), $data)) {
                    /** @var FieldInterface $field */
                    $field = $this->fieldInterfaceFactory->create();
                    $field->setType($formElement->getType())
                        ->setName($formElement->getName())
                        ->setValue($this->prepareFormResponseValue($response, $formElement, $data[$formElement->getName()]));
                    $fields[] = $field;
                } else {
                    throw new LocalizedException(__("Illegal access!"));
                }
            }
            $response->setFields($fields);
            $this->responseRepository->save($response);

            $result->setStatus(true)->setMessage(__($this->variableFieldRenderer->render($form->getResponseMessage(), $data)));
        } catch (IllegalEntryException $illegalEntryException) {
            $result->setStatus(false)->setMessage($illegalEntryException->getMessage());
        } catch (NoSuchEntityException $e) {
            $result->setStatus(false)->setMessage("Form no longer exists");
        } catch (CouldNotSaveException $couldNotSaveException) {
            $result->setStatus(false)->setMessage("Could not submit form. Please try again");
        }
        return $result;
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $response
     * @param ElementInterface $element
     * @param string $value
     * @return string
     * @throws IllegalEntryException
     */
    protected function prepareFormResponseValue($response, $element, $value)
    {
        if ($element->getType() == ElementInterface::TYPE_FILE) {
            $value = $this->uploadFileAndGetPath(
                $element->getName(),
                $response->getId() . '/' . $this->sanitizeDirectoryName($element->getName())
            );
        }
        return $value;
    }

    /**
     * @param string $fileId
     * @param string $destinationPath
     * @return string
     * @throws IllegalEntryException
     */
    protected function uploadFileAndGetPath($fileId, $destinationPath)
    {
        $suffix = 'dynamic_form' . DIRECTORY_SEPARATOR . $destinationPath;
        $destinationPath = $this->getDestinationPath($suffix);
        try {
            $uploader = $this->uploaderFactory->create(['fileId' => $fileId])
                ->setAllowCreateFolders(true)
                ->setAllowedExtensions();
            $destinationFile = $uploader->save($destinationPath);
            if (!$destinationFile) {
                throw new IllegalEntryException(
                    __('File cannot be saved to path: $1', $destinationPath)
                );
            }
            $value = $suffix . DIRECTORY_SEPARATOR . $destinationFile['file'];
        } catch (\Exception $e) {
            throw new IllegalEntryException(__("Error uploading file. Please try again"));
        }
        return $value;
    }

    public function getDestinationPath($suffix = '')
    {
        try {
            return $this->fileSystem
                ->getDirectoryWrite(DirectoryList::VAR_DIR)
                ->getAbsolutePath($suffix);
        } catch (FileSystemException $e) {
            $this->messageManager->addError(
                __($e->getMessage())
            );
        }
    }

    protected function sanitizeDirectoryName($file)
    {
        // Remove anything which isn't a word, whitespace, number
        // or any of the following caracters -_~,;[]().
        // If you don't need to handle multi-byte characters
        // you can use preg_replace rather than mb_ereg_replace
        $file = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $file);
        $file = mb_ereg_replace("([\.]{2,})", '', $file);
        return $file;
    }

    /**
     * @param int $productId
     * @return \Codilar\DynamicForm\Api\Data\FormItemInterface[]
     */
    public function getFormsByProduct($productId)
    {
        $product = $this->productHelper->getProduct($productId);
        $customForms = $product->getData("custom_forms");
        $forms = [];
        if ($customForms) {
            $customForms = explode(",", $customForms);
            if (is_array($customForms)) {
                foreach ($customForms as $customForm) {
                    try {
                        $form = $this->formRepository->load($customForm);
                    } catch (NoSuchEntityException $e) {
                        $form = false;
                    }
                    if ($form) {
                        /** @var FormItemInterface $orderItemForm */
                        $orderItemForm = $this->formItemFactory->create();
                        $orderItemForm->setIdentifier($form->getIdentifier())
                            ->setLabel($form->getTitle());
                        $forms[] = $orderItemForm;
                    }
                }

            }
        }
        return $forms;
    }
}