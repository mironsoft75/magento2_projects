<?php

namespace Pimcore\ImportExport\Controller\GetLists;
ini_set('display_errors', 1);

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Response\Http\FileFactory;
use Pimcore\ImportExport\Helper\Data;

class Attributes extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var Data
     */
    private $helper;
    /**
     * @var FileFactory
     */
    private $fileFactory;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Data $helper,
        FileFactory $fileFactory
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->helper = $helper;
        $this->fileFactory = $fileFactory;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->getAttributesV2();
        return $this->resultPageFactory->create();
    }

    private function getAttributesV1()
    {
        $attributeSets = $this->helper->getAttributeSetsList();
        $attributesData = [];
        foreach ($attributeSets as $key => $value) {
            $attributes = $this->helper->getAttributeManagement()->getAttributes(\Magento\Catalog\Api\Data\ProductAttributeInterface::ENTITY_TYPE_CODE, $value);

            if (count($attributes)) {
                foreach ($attributes as $attribute) {
                    if ($attribute['frontend_label']) {
                        $attributesData[] = $key . "," . $attribute['frontend_label'] . "," . $attribute['attribute_code'];
                    }
                }
            }
        }
        $this->getCsvFile("attributes-list-for-pimcore.csv", $attributesData, "attribute set name,attribute name,attribute_code");

    }

    private function getAttributesV2()
    {
        $attributes = $this->helper->getAttributesList();
        $attributesData = [];
        foreach ($attributes as $key => $value) {
            $attributesData[] = $value->getData('frontend_label') . "," . $value->getData('attribute_code');
        }
        $this->getCsvFile("attributes-list-for-pimcore.csv", $attributesData, "attribute name,attribute_code");

    }

    /**
     * @param $name
     * @param $colValues
     * @param $heads
     */
    public function getCsvFile($name, $colValues, $heads)
    {
        $data = $heads . PHP_EOL;
        foreach ($colValues as $value) {
            $data .= "$value" . PHP_EOL;
        }
        $this->fileFactory->create(
            $name,
            $data,
            DirectoryList::VAR_DIR,
            'application/octet-stream'
        );
    }
}
