<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model;


use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Codilar\DynamicForm\Api\FormRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\DynamicForm\Model\Form as Model;
use Codilar\DynamicForm\Model\FormFactory as ModelFactory;
use Codilar\DynamicForm\Model\ResourceModel\Form as ResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Collection;
use Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory;
use Codilar\DynamicForm\Api\Data\FormInterface as Data;
use Codilar\DynamicForm\Api\Data\FormInterfaceFactory as DataFactory;
use Codilar\DynamicForm\Api\Data\FormSearchResultsInterface as SearchResults;
use Codilar\DynamicForm\Api\Data\FormSearchResultsInterfaceFactory as SearchResultsFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface as ExtensionAttributesJoinProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class FormRepository implements FormRepositoryInterface
{
    /**
     * @var FormFactory
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
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;
    /**
     * @var \Codilar\DynamicForm\Api\Data\FormInterface[]
     */
    private $dataCache;

    /**
     * FormRepository constructor.
     * @param FormFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param DataFactory $dataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param SearchResultsFactory $searchResultsFactory
     * @param ExtensionAttributesJoinProcessor $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param ElementRepositoryInterface $elementRepository
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
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        ElementRepositoryInterface $elementRepository,
        array $dataCache = []
    )
    {
        $this->modelFactory = $modelFactory;
        $this->resourceModel = $resourceModel;
        $this->collectionFactory = $collectionFactory;
        $this->dataFactory = $dataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->elementRepository = $elementRepository;
        $this->dataCache = $dataCache;
    }

    /**
     * @param string $value
     * @param string|null $field
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
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
            $formElements = [];
            foreach (explode(',', $model->getData(Model::FORM_ELEMENT_IDS)) as $formElementId) {
                if (!$formElementId) {
                    continue;
                }
                try {
                    $formElement = $this->elementRepository->load($formElementId);
                } catch (NoSuchEntityException $e) {
                    $formElement = $this->elementRepository->create();
                    $formElement->setId($formElementId);
                }
                $formElements[] = $formElement;
            }
            $dataModel->setFormElements($formElements);
            $this->dataCache[$key] = $dataModel;
        }
        return $this->dataCache[$key];
    }

    /**
     * @param \Codilar\DynamicForm\Model\Form $model
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function getDataModel(\Codilar\DynamicForm\Model\Form $model)
    {
        $form = $this->create();
        $this->dataObjectHelper->populateWithArray(
            $form,
            $model->getData(),
            Data::class
        );
        return $form;
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     */
    public function create()
    {
        return $this->dataFactory->create();
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\FormInterface $form
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(\Codilar\DynamicForm\Api\Data\FormInterface $form)
    {
        $model = $this->getModelById($form->getId());
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__("Error deleting form"));
        }
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\FormInterface $form
     * @return \Codilar\DynamicForm\Api\Data\FormInterface
     * @throws CouldNotSaveException
     */
    public function save(\Codilar\DynamicForm\Api\Data\FormInterface $form)
    {
        if (!$this->validateIdentifier($form)) {
            throw new CouldNotSaveException(__("Identifier needs to be unique for all forms sharing the same store"));
        }
        $model = $this->getModelById($form->getId());
        $formData = $this->extensibleDataObjectConverter->toNestedArray($form, [], Data::class);
        $model->setData($formData);

        $formElementIds = [];
        if ($form->getFormElements()) {
            foreach ($form->getFormElements() as $formElement) {
                if ($formElement->getIdentifier()) {
                    $formElementIds[] = $formElement->getId();
                }
            }
        }
        $model->setData(Model::FORM_ELEMENT_IDS, implode(',', $formElementIds));

        try {
            $this->resourceModel->save($model);
            return $this->getDataModel($model);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__("Error saving form"));
        }
    }

    /**
     * @param Data $form
     * @return bool
     */
    protected function validateIdentifier($form)
    {
        return $this->collectionFactory->create()
            ->addFieldToFilter('identifier', $form->getIdentifier())
            ->addFieldToFilter('store_views', $form->getStoreViews())
            ->addFieldToFilter('id', ['neq' => $form->getId()])
            ->getSize() === 0;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return SearchResults
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
        $forms = [];
        /** @var Model $model */
        foreach ($collection as $model) {
            $forms[] = $this->getDataModel($model);
        }
        $searchResults->setItems($forms);
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
}