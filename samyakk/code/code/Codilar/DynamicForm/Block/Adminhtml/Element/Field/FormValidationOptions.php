<?php
/**
 * @package     magento2
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Block\Adminhtml\Element\Field;


use Codilar\DynamicForm\Model\Config\Source\Element\ValidationTypes;
use Magento\Backend\Block\Template;
use Codilar\DynamicForm\Model\ResourceModel\Form\Element\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;


class FormValidationOptions extends Template
{
    /**
     * @var ValidationTypes
     */
    private $validationTypes;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;
    /**
     * @var array
     */
    private $loadedData;

    /**
     * FormValidationOptions constructor.
     * @param Template\Context $context
     * @param ValidationTypes $validationTypes
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ValidationTypes $validationTypes,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->validationTypes = $validationTypes;
        $this->collectionFactory = $collectionFactory;
        $this->dataPersistor = $dataPersistor;
    }

    protected $_template = "Codilar_DynamicForm::element/field/form_validation_options.phtml";

    public function getValidations()
    {
        return $this->validationTypes->toOptionArray();
    }

    public function getValues() {
        $data = $this->getLoadedData();
        return isset($data[$this->getRequest()->getParam('id')]) ? \json_decode($data[$this->getRequest()->getParam('id')]['validation_json'], true) : [];
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