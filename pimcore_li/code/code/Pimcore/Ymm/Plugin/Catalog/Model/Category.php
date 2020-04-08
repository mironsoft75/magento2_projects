<?php

namespace Pimcore\Ymm\Plugin\Catalog\Model;

use Magento\Catalog\Model\ResourceModel\ProductFactory;
use Pimcore\Ymm\Helper\Data;
use Psr\Log\LoggerInterface;

/**
 * Class Config
 * @package Pimcore\Common\Plugin\Catalog\Model
 */
class Category
{

    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var ProductFactory
     */
    private $productFactory;
    /**
     * @var Data
     */
    private $helper;

    /**
     * Config constructor.
     *
     */
    public function __construct(
        LoggerInterface $logger,
        ProductFactory $productFactory,
        Data $helper
    )
    {
        $this->logger = $logger;
        $this->productFactory = $productFactory;
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param                               $options
     * @return array
     */
    public function afterGetName(\Magento\Catalog\Model\Category $category, $result)
    {
        try {
            if ($this->helper->isMakeandModelPage()) {
                $makeNameKey = \Pimcore\Ymm\Model\YmmApiManagement::MAKE_COL;
                $modelNameKey = \Pimcore\Ymm\Model\YmmApiManagement::MODEL_COL;
                $result = $this->getAttributeOptionText($makeNameKey, $this->helper->getSelectedYmm($makeNameKey)) .
                    ' ' .
                    $this->getAttributeOptionText($modelNameKey, $this->helper->getSelectedYmm($modelNameKey));
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return $result;
    }

    /**
     * @param $attribute
     * @param $label
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributeOptionText($attribute, $label)
    {
        $optionText = '';
        $poductReource = $this->productFactory->create();
        $attr = $poductReource->getAttribute($attribute);
        if ($attr->usesSource()) {
            $label = explode(",", $label);
            foreach ($label as $labelValue) {
                $optionText .= $attr->getSource()->getOptionText($labelValue) . ' ';
            }

        }
        return $optionText;
    }

    /**
     * @param $attribute
     * @param $label
     * @return null|string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributeOptionId($attribute, $label)
    {
        $optionId = '';
        $poductReource = $this->productFactory->create();
        $attr = $poductReource->getAttribute($attribute);
        if ($attr && $attr->usesSource()) {
            $optionId = $attr->getSource()->getOptionId($label);
        }
        return $optionId;
    }
}