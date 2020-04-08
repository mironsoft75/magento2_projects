<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Model\Form;


use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Codilar\DynamicForm\Model\Form\Element as Model;
use Codilar\DynamicForm\Model\Form\ElementFactory as ModelFactory;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element as ResourceModel;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element\Collection;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element\CollectionFactory;
use Codilar\DynamicForm\Api\Data\Form\ElementInterface as Data;
use Codilar\DynamicForm\Api\Data\Form\ElementInterfaceFactory as DataFactory;
use Codilar\DynamicForm\Api\Data\Form\ElementSearchResultsInterface as SearchResults;
use Codilar\DynamicForm\Api\Data\Form\ElementSearchResultsInterfaceFactory as SearchResultsFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface as ExtensionAttributesJoinProcessor;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Codilar\DynamicForm\Api\Data\Form\Element\OptionInterface as FormElementOption;
use Codilar\DynamicForm\Api\Data\Form\Element\OptionInterfaceFactory as FormElementOptionFactory;
use Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterfaceFactory;
use Codilar\DynamicForm\Api\Data\Form\Element\ValidationOptionsInterface;


class ElementRepository implements ElementRepositoryInterface
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
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;
    /**
     * @var FormElementOptionFactory
     */
    private $formElementOptionFactory;
    /**
     * @var \Codilar\DynamicForm\Api\Data\Form\ElementInterface[]
     */
    private $dataCache;
    /**
     * @var ValidationOptionsInterfaceFactory
     */
    private $validationOptionsInterfaceFactory;

    /**
     * ElementRepository constructor.
     * @param ElementFactory $modelFactory
     * @param ResourceModel $resourceModel
     * @param CollectionFactory $collectionFactory
     * @param DataFactory $dataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param SearchResultsFactory $searchResultsFactory
     * @param ExtensionAttributesJoinProcessor $extensionAttributesJoinProcessor
     * @param CollectionProcessorInterface $collectionProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     * @param FormElementOptionFactory $formElementOptionFactory
     * @param ValidationOptionsInterfaceFactory $validationOptionsInterfaceFactory
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
        FormElementOptionFactory $formElementOptionFactory,
        ValidationOptionsInterfaceFactory $validationOptionsInterfaceFactory,
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
        $this->formElementOptionFactory = $formElementOptionFactory;
        $this->dataCache = $dataCache;
        $this->validationOptionsInterfaceFactory = $validationOptionsInterfaceFactory;
    }

    /**
     * @param string $value
     * @param string|null $field
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
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

            $optionData = \json_decode($model->getData(Model::OPTIONS_JSON), true);
            $options = [];
            if (is_array($optionData)) {
                foreach ($optionData as $optionDatum) {
                    /** @var FormElementOption $option */
                    $option = $this->formElementOptionFactory->create();
                    $option->setLabel($optionDatum['label']);
                    $option->setValue($optionDatum['value']);
                    $options[] = $option;
                }
            }
            $dataModel->setOptions($options);

            $validationOptionData = \json_decode($model->getData(Model::VALIDATION_JSON), true);
            $validationOptions = [];
            if (is_array($validationOptionData)) {
                foreach ($validationOptionData as $validationOptionDatum) {
                    /** @var ValidationOptions $validationOption */
                    $validationOption = $this->validationOptionsInterfaceFactory->create();
                    $validationOption->setLabel($validationOptionDatum['label']);
                    $validationOption->setValue($validationOptionDatum['value']);
                    $validationOptions[] = $validationOption;
                }
            }
            $dataModel->setValidation($validationOptions);

            $this->dataCache[$key] = $dataModel;
        }
        return $this->dataCache[$key];
    }

    /**
     * @param \Codilar\DynamicForm\Model\Form\Element $model
     * @return Data
     */
    public function getDataModel(\Codilar\DynamicForm\Model\Form\Element $model)
    {
        $formElement = $this->create();
        $this->dataObjectHelper->populateWithArray(
            $formElement,
            $model->getData(),
            Data::class
        );
        return $formElement;
    }

    /**
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
     */
    public function create()
    {
        return $this->dataFactory->create();
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement
     * @return $this
     * @throws CouldNotDeleteException
     */
    public function delete(\Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement)
    {
        $model = $this->getModelById($formElement->getId());
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $e) {
            throw new CouldNotDeleteException(__("Error deleting form element"));
        }
    }

    /**
     * @param \Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementInterface
     * @throws CouldNotSaveException
     */
    public function save(\Codilar\DynamicForm\Api\Data\Form\ElementInterface $formElement)
    {
        if (!$this->validateIdentifier($formElement)) {
            throw new CouldNotSaveException(__("Identifier needs to be unique for all form elements sharing the same store"));
        }
        $model = $this->getModelById($formElement->getId());
        $formElementData = $this->extensibleDataObjectConverter->toNestedArray($formElement, [], Data::class);
        $model->setData($formElementData);
        $options = [];
        if ($formElement->getOptions()) {
            foreach ($formElement->getOptions() as $option) {
                $options[] = [
                    'label' => $option->getLabel(),
                    'value' => $option->getValue()
                ];
            }
        }
        $model->setData(Model::OPTIONS_JSON, \json_encode($options));

        $validationOptions = [];
        if ($formElement->getValidation()) {
            foreach ($formElement->getValidation() as $validationOption) {
                $validationOptions[] = [
                    'label' => $validationOption->getLabel(),
                    'value' => $validationOption->getValue()
                ];
            }
        }
        $model->setData(Model::VALIDATION_JSON, \json_encode($validationOptions));

        try {
            $this->resourceModel->save($model);
            return $this->getDataModel($model);
        } catch (AlreadyExistsException $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__("Error saving form element"));
        }
    }

    /**
     * @param Data $formElement
     * @return bool
     */
    protected function validateIdentifier($formElement)
    {
        return $this->collectionFactory->create()
                ->addFieldToFilter('identifier', $formElement->getIdentifier())
                ->addFieldToFilter('store_views', $formElement->getStoreViews())
                ->addFieldToFilter('id', ['neq' => $formElement->getId()])
                ->getSize() === 0;
    }

    /**
     * @param \Magento\Framework\Api\SearchCriteria $searchCriteria
     * @return \Codilar\DynamicForm\Api\Data\Form\ElementSearchResultsInterface
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
}