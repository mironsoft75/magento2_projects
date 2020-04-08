<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Form;


use Codilar\DynamicForm\Api\Form\ResponseRepositoryInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Codilar\DynamicForm\Exception\CouldNotNotifyException;
use Codilar\DynamicForm\Helper\Email as EmailHelper;
use Codilar\DynamicForm\Helper\VariableFieldRenderer;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\MailException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\DynamicForm\Model\Form\Response as Model;
use Codilar\DynamicForm\Model\Form\ResponseFactory as ModelFactory;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response as ResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Collection;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\CollectionFactory;
use Codilar\DynamicForm\Api\Data\Form\ResponseInterface as Data;
use Codilar\DynamicForm\Api\Data\Form\ResponseInterfaceFactory as DataFactory;
use Codilar\DynamicForm\Api\Data\Form\ResponseSearchResultsInterface as SearchResults;
use Codilar\DynamicForm\Api\Data\Form\ResponseSearchResultsInterfaceFactory as SearchResultsFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface as ExtensionAttributesJoinProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field\Collection as FormResponseFieldCollection;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field\CollectionFactory as FormResponseFieldCollectionFactory;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterface;
use Codilar\DynamicForm\Api\Data\Form\Response\FieldInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Codilar\DynamicForm\Model\Form\Response\Field as FieldModel;
use Codilar\DynamicForm\Model\Form\Response\FieldFactory as FieldModelFactory;
use Codilar\DynamicForm\Model\ResourceModel\Form\Response\Field as FieldResourceModel;


class ResponseRepository implements ResponseRepositoryInterface
{
    /**
     * @var ElementFactory
     */
    private $modelFactory;
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataFactory
     */
    private $dataFactory;
    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;
    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;
    /**
     * @var ExtensionAttributesJoinProcessor
     */
    private $extensionAttributesJoinProcessor;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var \Codilar\DynamicForm\Api\Data\Form\ResponseInterface[]
     */
    private $dataCache;
    /**
     * @var FormResponseFieldCollectionFactory
     */
    private $formResponseFieldCollectionFactory;
    /**
     * @var FieldInterfaceFactory
     */
    private $fieldInterfaceFactory;
    /**
     * @var DataObjectProcessor
     */
    private $dataObjectProcessor;
    /**
     * @var FieldModelFactory
     */
    private $fieldModelFactory;
    /**
     * @var FieldResourceModel
     */
    private $fieldResourceModel;
    /**
     * @var EmailHelper
     */
    private $emailHelper;
    /**
     * @var FormRepositoryInterface
     */
    private $formRepository;
    /**
     * @var VariableFieldRenderer
     */
    private $variableFieldRenderer;

    /**
     * ResponseRepository constructor.
     * @param ResponseFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param DataFactory $dataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param SearchResultsFactory $searchResultsFactory
     * @param ExtensionAttributesJoinProcessor $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param FormResponseFieldCollectionFactory $formResponseFieldCollectionFactory
     * @param FieldInterfaceFactory $fieldInterfaceFactory
     * @param DataObjectProcessor $dataObjectProcessor
     * @param FieldModelFactory $fieldModelFactory
     * @param FieldResourceModel $fieldResourceModel
     * @param EmailHelper $emailHelper
     * @param FormRepositoryInterface $formRepository
     * @param VariableFieldRenderer $variableFieldRenderer
     * @param array $dataCache
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resourceModel,
        CollectionFactory $collectionFactory,
        DataFactory $dataFactory,
        DataObjectHelper $dataObjectHelper,
        SearchResultsFactory $searchResultsFactory,
        ExtensionAttributesJoinProcessor $extensionAttributesJoinProcessor,
        CollectionProcessorInterface $collectionProcessor,
        FormResponseFieldCollectionFactory $formResponseFieldCollectionFactory,
        FieldInterfaceFactory $fieldInterfaceFactory,
        DataObjectProcessor $dataObjectProcessor,
        FieldModelFactory $fieldModelFactory,
        FieldResourceModel $fieldResourceModel,
        EmailHelper $emailHelper,
        FormRepositoryInterface $formRepository,
        VariableFieldRenderer $variableFieldRenderer,
        array $dataCache = []
    ) {

        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->dataFactory = $dataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->dataCache = $dataCache;
        $this->formResponseFieldCollectionFactory = $formResponseFieldCollectionFactory;
        $this->fieldInterfaceFactory = $fieldInterfaceFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->fieldModelFactory = $fieldModelFactory;
        $this->fieldResourceModel = $fieldResourceModel;
        $this->emailHelper = $emailHelper;
        $this->formRepository = $formRepository;
        $this->variableFieldRenderer = $variableFieldRenderer;
    }

    /**
     * @param string $value
     * @param string|null $field
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null)
    {
        $key = $value . '_' . $field;
        if (!array_key_exists($key, $this->dataCache)) {
            /** @var Model $model */
            $model = $this->modelFactory->create();
            $this->resourceModel->load($model, $value, $field);
            if (!$model->getId()) {
                throw NoSuchEntityException::singleField($field, $value);
            }
            $dataModel = $this->getDataModel($model);

            /** @var FormResponseFieldCollection $fieldsCollection */
            $fieldsCollection = $this->formResponseFieldCollectionFactory->create();
            $fieldsCollection->addFieldToFilter(FieldInterface::FORM_RESPONSE_ID, $dataModel->getId());
            $fields = [];
            /** @var Model $item */
            foreach ($fieldsCollection as $item) {
                /** @var FieldInterface $field */
                $field = $this->fieldInterfaceFactory->create();
                $field->setId($item->getId())
                    ->setFormResponseId($item->getData(FieldInterface::FORM_RESPONSE_ID))
                    ->setType($item->getData(FieldInterface::TYPE))
                    ->setName($item->getData(FieldInterface::NAME))
                    ->setValue($item->getData(FieldInterface::VALUE));
                $fields[] = $field;
            }
            $dataModel->setFields($fields);
            $this->dataCache[$key] = $dataModel;
        }
        return $this->dataCache[$key];
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     */
    public function create()
    {
        return $this->dataFactory->create();
    }

    /**
     * @param \Codilar\DynamicForm\Model\Form\Response $model
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     */
    public function getDataModel(\Codilar\DynamicForm\Model\Form\Response $model)
    {
        $formResponse = $this->create();
        $this->dataObjectHelper->populateWithArray(
            $formResponse,
            $model->getData(),
            Data::class
        );
        return $formResponse;
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(\Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse)
    {
        $model = $this->getModelById($formResponse->getId());
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__("Error deleting form response"));
        }
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse
     * @param bool $notify
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseInterface
     * @throws CouldNotSaveException
     */
    public function save(\Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse, $notify = true)
    {
        $model = $this->getModelById($formResponse->getId());
        $formElementData = $this->dataObjectProcessor->buildOutputDataArray($formResponse, Data::class);
        $model->setData($formElementData);
        try {
            $this->resourceModel->save($model);
            $dataModel = $this->getDataModel($model);
            foreach ($formResponse->getFields() as $field) {
                /** @var FieldModel $fieldModel */
                $fieldModel = $this->fieldModelFactory->create();
                $this->fieldResourceModel->load($fieldModel, $field->getId());
                $fieldModel->addData([
                    FieldInterface::FORM_RESPONSE_ID => $dataModel->getId(),
                    FieldInterface::TYPE => $field->getType(),
                    FieldInterface::NAME => $field->getName(),
                    FieldInterface::VALUE => $field->getValue()
                ]);
                $this->fieldResourceModel->save($fieldModel);
                $field->setId($fieldModel->getId())
                    ->setFormResponseId($fieldModel->getData(FieldInterface::FORM_RESPONSE_ID))
                    ->setType($fieldModel->getData(FieldInterface::TYPE))
                    ->setName($fieldModel->getData(FieldInterface::NAME))
                    ->setValue($fieldModel->getData(FieldInterface::VALUE));
            }
            $dataModel->setFields($formResponse->getFields());
            if ($notify) {
                $this->notify($dataModel);
            }
            return $dataModel;
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (CouldNotNotifyException $couldNotNotifyException) {
            throw new CouldNotSaveException(__($couldNotNotifyException->getMessage()));
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__("Error saving form response"));
        }
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Codilar\DynamicForm\Api\Data\Form\ResponseSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteria $searchCriteria)
    {
        /** @var SearchResults $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var Collection $collection */
        $collection = $this->collectionFactory->create();
        $this->extensionAttributesJoinProcessor->process(
            $collection,
            Data::class
        );
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults->setTotalCount($collection->getSize());
        $formElements = [];
        /** @var Model $model */
        foreach ($collection as $model) {
            $formElements[] = $this->getDataModel($model);
        }
        $searchResults->setItems($formElements);
        return $searchResults;
    }

    /**
     * @param int $id
     * @return Model
     */
    protected function getModelById($id)
    {
        /** @var Model $model */
        $model = $this->modelFactory->create();
        $this->resourceModel->load($model, $id);
        return $model;
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse
     * @return $this
     * @throws CouldNotNotifyException
     * @throws NoSuchEntityException
     */
    public function notify(\Codilar\DynamicForm\Api\Data\Form\ResponseInterface $formResponse)
    {
        $form = $this->formRepository->load($formResponse->getFormId());
        $copyToEmails = explode(',', $form->getSendEmailCopyTo());
        $toName = '';
        $data = [
            'data'  =>  new \Magento\Framework\DataObject([
                'id'  =>  $formResponse->getId(),
                'form_id'  =>  $formResponse->getFormId(),
                'created_at'  =>  $formResponse->getCreatedAt(),
                'customer_email'  =>  $formResponse->getCustomerEmail(),
                'customer_ip'  =>  $formResponse->getCustomerIp(),
                'fields'  =>  []
            ])
        ];
        $fields = [];
        foreach ($formResponse->getFields() as $field) {
            foreach ($copyToEmails as $copyToEmailKey => $copyToEmail) {
                if ($copyToEmail === '%' . $field->getName()) {
                    $copyToEmails[$copyToEmailKey] = $field->getValue();
                }
            }
            $fields[$field->getName()] = $field->getValue();
        }
        $data['data']->setFields(new \Magento\Framework\DataObject($fields));
        $data['data']->setResponseMessage($this->variableFieldRenderer->render($form->getResponseMessage(), $fields));

        foreach ($copyToEmails as $copyToEmail) {
            $copyToEmail = trim($copyToEmail);
            if ($copyToEmail && strlen($copyToEmail)) {
                try {
                    $this->emailHelper->sendEmail($form->getEmailTemplate(), $form->getEmailSender(), $copyToEmail, $toName, $data);
                } catch (MailException $e) {
                    throw new CouldNotNotifyException(__($e->getMessage()));
                }
            }
        }
        return $this;
    }
}