<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\Element\Field;

use Codilar\DynamicForm\Api\Data\Form\ElementInterface;
use Magento\Backend\Block\Template;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element\CollectionFactory;

class FormElementOptions extends Template
{
    protected $_template = "Codilar_DynamicForm::element/field/form_element_options.phtml";
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
     * @var array
     */
    private $loadedData;

    /**
     * FormElements constructor.
     * @param Template\Context $context
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
     * @param DataPersistorInterface $dataPersistor
     * @param CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        DataPersistorInterface $dataPersistor,
        CollectionFactory $collectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
        $this->dataPersistor = $dataPersistor;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return int[]
     */
    public function getOptionableTypes() {
        return [
            ElementInterface::TYPE_SELECT,
            ElementInterface::TYPE_MULTISELECT,
            ElementInterface::TYPE_RADIO
        ];
    }

    public function getTypeValue()
    {
        $data = $this->getLoadedData();
        return isset($data[$this->getRequest()->getParam('id')]) ? $data[$this->getRequest()->getParam('id')]['type'] : ElementInterface::TYPE_TEXT;
    }

    public function getValues() {
        $data = $this->getLoadedData();
        return isset($data[$this->getRequest()->getParam('id')]) ? \json_decode($data[$this->getRequest()->getParam('id')]['options_json'], true) : [];
    }

    protected function getLoadedData()
    {
        if(!$this->loadedData) {
            $items = $this->collectionFactory->create()->addFieldToFilter('id', $this->getRequest()->getParam('id'))->getItems();
            $data = [];
            /** @var \Codilar\DynamicForm\Model\Form\Element $formElement */
            foreach ($items as $formElement) {
                $data[$formElement->getId()] = $formElement->getData();
            }
            $previousData = $this->dataPersistor->get('dynamicform_form_element');
            if (!empty($previousData)) {
                $formElement = $this->collectionFactory->create()->getNewEmptyItem();
                $formElement->setData($previousData);
                $data[$formElement->getId()] = $formElement->getData();
            }
            $this->loadedData = $data;
        }
        return $this->loadedData;
    }
}