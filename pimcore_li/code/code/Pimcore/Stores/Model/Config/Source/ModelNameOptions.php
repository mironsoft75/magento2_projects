<?php

namespace Pimcore\Stores\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory;
use Magento\Framework\DB\Ddl\Table;
use Pimcore\Ymm\Api\YmmApiManagementInterface;

class ModelNameOptions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var OptionFactory
     */
    protected $optionFactory;
    /**
     * @var YmmApiManagementInterface
     */
    private $ymmApiManagement;

    /**
     * @param OptionFactory             $optionFactory
     * @param YmmApiManagementInterface $ymmApiManagement
     */
    public function __construct(
        OptionFactory $optionFactory,
        YmmApiManagementInterface $ymmApiManagement
    )
    {
        $this->optionFactory = $optionFactory;
        //you can use this if you want to prepare options dynamically
        $this->ymmApiManagement = $ymmApiManagement;
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function getAllOptions()
    {
        $this->_options = $this->getAllUniqueModelNames();
        return $this->_options;
    }

    /**
     * @return array
     */
    private function getAllUniqueModelNames()
    {
        $modelNames = $this->ymmApiManagement->getModelNames();
        $result = [];
        foreach ($modelNames as $model) {
            $result[] = ['label' => $model, 'value' => $model];
        }
        return $result;
    }

    /**
     * Get a text for option value
     *
     * @param string|integer $value
     * @return string|bool
     */
    public function getOptionText($value)
    {
        foreach ($this->getAllOptions() as $option) {
            if ($option['value'] == $value) {
                return $option['label'];
            }
        }
        return false;
    }

    /**
     * Retrieve flat column definition
     *
     * @return array
     */
    public function getFlatColumns()
    {
        $attributeCode = $this->getAttribute()->getAttributeCode();
        return [
            $attributeCode => [
                'unsigned' => false,
                'default' => null,
                'extra' => null,
                'type' => Table::TYPE_TEXT,
                'nullable' => true,
                'comment' => 'Custom Attribute Options  ' . $attributeCode . ' column',
            ],
        ];
    }
}
