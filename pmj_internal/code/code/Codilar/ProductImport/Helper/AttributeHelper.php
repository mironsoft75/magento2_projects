<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/7/19
 * Time: 8:33 PM
 */

namespace Codilar\ProductImport\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttribute;
use Codilar\MasterTables\Api\VariantNameRepositoryInterface;
use Codilar\MasterTables\Api\MetalRepositoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class AttributeHelper
 * @package Codilar\ProductImport\Helper
 */
class AttributeHelper extends AbstractHelper
{
    /**
     * @var AttributeFactory
     */
    protected $_eavAttributeFactory;
    /**
     * @var AttributeOptionManagementInterface
     */
    protected $_attributeOptionManagement;
    /**
     * @var ProductAttribute
     */
    protected $_productAttribute;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var VariantNameRepositoryInterface
     */
    protected $_variantNameRepositoryInterface;
    /**
     * @var MetalRepositoryInterface
     */
    protected $_metalRepositoryInterface;

    /**
     * AttributeHelper constructor.
     * @param AttributeFactory $attributeFactory
     * @param AttributeOptionManagementInterface $attributeOptionManagement
     * @param ProductAttribute $attribute
     * @param LoggerInterface $logger
     * @param VariantNameRepositoryInterface $variantNameRepository
     * @param MetalRepositoryInterface $metalRepository
     * @param Context $context
     */
    public function __construct
    (
        AttributeFactory $attributeFactory,
        AttributeOptionManagementInterface $attributeOptionManagement,
        ProductAttribute $attribute,
        LoggerInterface $logger,
        VariantNameRepositoryInterface $variantNameRepository,
        MetalRepositoryInterface $metalRepository,
        Context $context
    )
    {
        $this->_eavAttributeFactory = $attributeFactory;
        $this->_attributeOptionManagement = $attributeOptionManagement;
        $this->_productAttribute = $attribute;
        $this->_variantNameRepositoryInterface = $variantNameRepository;
        $this->_metalRepositoryInterface = $metalRepository;
        $this->_logger = $logger;
        parent::__construct($context);
    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return string
     */
    public function addAttributeOptions($attributeName, $attributeValue)
    {
        try {
            /**
             * @var $magentoAttribute \Magento\Eav\Model\Entity\Attribute
             */
            $magentoAttribute = $this->_eavAttributeFactory->create()->loadByCode('catalog_product', $attributeName);

            $attributeCode = $magentoAttribute->getAttributeCode();
            $magentoAttributeOptions = $this->_attributeOptionManagement->getItems(
                'catalog_product',
                $attributeCode
            );
            $attributeOptions = [$attributeValue];
            $existingMagentoAttributeOptions = [];
            $newOptions = [];
            $counter = 0;
            foreach ($magentoAttributeOptions as $option) {
                if (!$option->getValue()) {
                    continue;
                }
                if ($option->getLabel() instanceof \Magento\Framework\Phrase) {
                    $label = $option->getText();
                } else {
                    $label = $option->getLabel();
                }

                if ($label == '') {
                    continue;
                }
                $existingMagentoAttributeOptions[] = $label;
                $newOptions['value'][$option->getValue()] = [$label, $label];
                $counter++;
            }

            foreach ($attributeOptions as $option) {
                if ($option == '') {
                    continue;
                }
                if (!in_array($option, $existingMagentoAttributeOptions)) {
                    $newOptions['value']['option_' . $counter] = [$option, $option];
                }

                $counter++;
            }

            if (count($newOptions)) {
                $magentoAttribute->setOption($newOptions)->save();
            }
            return $this->setNewAttributeValues($attributeName, $attributeValue);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }


    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return string
     */
    public function setNewAttributeValues($attributeName, $attributeValue)
    {
        try {
            $attributes = $this->_productAttribute->get($attributeName)->getOptions();
            foreach ($attributes as $attributeOption) {
                if ($attributeOption->getValue() != '' && $attributeOption->getLabel() == $attributeValue) {
                    return $attributeOption->getValue();
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $attributeName
     * @param $attributeValue
     * @return string
     */
    public function setAttributeValues($attributeName, $attributeValue)
    {
        try {
            $sizes = $this->_productAttribute->get($attributeName)->getOptions();
            foreach ($sizes as $sizesOption) {
                if ($sizesOption->getValue() != '') {
                    if ($sizesOption->getLabel() == $attributeValue) {
                        return $sizesOption->getValue();
                    } else {
                        return $this->addAttributeOptions($attributeName, $attributeValue);
                    }

                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $variantName
     * @return mixed
     */
    public function getProductType($variantName)
    {
        try {
            $variantDetails = $this->_variantNameRepositoryInterface->getVariantName($variantName);
            return $variantDetails->getProductType();
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $karatColor
     * @return mixed
     */
    public function getMetalDetails($karatColor)
    {
        try {
            return $this->_metalRepositoryInterface->getByKaratColor($karatColor);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }
}