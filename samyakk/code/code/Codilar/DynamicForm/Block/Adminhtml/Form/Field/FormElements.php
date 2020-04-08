<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\Form\Field;


use Codilar\DynamicForm\Api\Form\ElementRepositoryInterface;
use Magento\Backend\Block\Template;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form\CollectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;

class FormElements extends Template
{
    protected $_template = "Codilar_DynamicForm::form/field/form_elements.phtml";
    /**
     * @var ElementRepositoryInterface
     */
    private $elementRepository;
    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * FormElements constructor.
     * @param Template\Context $context
     * @param ElementRepositoryInterface $elementRepository
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param DataPersistorInterface $dataPersistor
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ElementRepositoryInterface $elementRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->elementRepository = $elementRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->dataPersistor = $dataPersistor;
        $this->collectionFactory = $collectionFactory;
    }

    public function getFormElements() {
        $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
        $searchCriteria = $searchCriteriaBuilder->create();
        $elements = $this->elementRepository->getList($searchCriteria);
        $formElements = [];
        $formElements[] = [
            'label' =>  __("-- Choose a form element --"),
            'value' => ''
        ];
        foreach ($elements->getItems() as $element) {
            $formElements[] = [
                'label' =>  $element->getLabel() . ' - ' . $element->getIdentifier() . ' (' . $element->getId() . ')',
                'value' =>  $element->getId()
            ];
        }
        return $formElements;
    }

    public function getValues() {
        $items = $this->collectionFactory->create()->addFieldToFilter('id', $this->getRequest()->getParam('id'))->getItems();
        $data = [];
        /** @var \Codilar\DynamicForm\Model\Form $form */
        foreach ($items as $form) {
            $data[$form->getId()] = $form->getData();
        }
        $previousData = $this->dataPersistor->get('dynamicform_form');
        if (!empty($previousData)) {
            $form = $this->collectionFactory->create()->getNewEmptyItem();
            $form->setData($previousData);
            $data[$form->getId()] = $form->getData();
        }
        $values = [];
        $data = isset($data[$this->getRequest()->getParam('id')]) ? explode(',', $data[$this->getRequest()->getParam('id')]['form_element_ids']) : [];
        foreach ($data as $value) {
            try {
                $this->elementRepository->load($value);
                $values[] = [
                    'form_element_id' => $value
                ];
            } catch (NoSuchEntityException $exception) {}
        }
        return $values;
    }
}