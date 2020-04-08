<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Codilar\Rapnet\Helper;

use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;
use Codilar\Rapnet\Api\RapnetRepositoryInterface;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as AttributeCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Action;
use Magento\Quote\Model\Quote;

/**
 * Class Data
 * @package Codilar\Rapnet\Helper
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const DIAMOND_PRODUCT = "Diamond Product";
    const XML_PATH_RAPNET_ENABLED = 'rapnet_section/settings/rapnet';
    const XML_PATH_USERNAME = 'rapnet_section/settings/username';
    const XML_PATH_PASSWORD = 'rapnet_section/settings/password';
    const XML_PATH_SLIDER_COLOR = 'rapnet_section/settings/slider_colour';
    const XML_PATH_HOVER_COLOR = 'rapnet_section/settings/hover_colour';
    const XML_PATH_SHAPE_FILTER = 'rapnet_section/settings/shape_filter';
    const XML_PATH_CUT_FILTER = 'rapnet_section/settings/cut_filter';
    const XML_PATH_COLOR_FILTER = 'rapnet_section/settings/color_filter';
    const XML_PATH_CLARITY_FILTER = 'rapnet_section/settings/clarity_filter';
    const XML_PATH_CERTIFICATE_FILTER = 'rapnet_section/settings/certificate_filter';
    const XML_PATH_POLISH_FILTER = 'rapnet_section/settings/polish_filter';
    const XML_PATH_SYMMETRY_FILTER = 'rapnet_section/settings/symmetry_filter';
    const XML_PATH_FLOURESCENCE_FILTER = 'rapnet_section/settings/fluorescence_filter';
    const XML_PATH_MANUFACTURING_DAYS = 'rapnet_section/settings/manufacturing_days';


    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var RapnetRepositoryInterface
     */
    private $rapnetRepository;
    /**
     * @var Config
     */
    protected $_eavConfig;
    /**
     * @var AttributeCollectionFactory
     */
    protected $_attributeSetCollection;
    /**
     * @var CollectionFactory
     */
    protected $_productCollection;
    /**
     * @var Action
     */
    protected $_updateAction;
    protected $_quote;
    protected $_productResource;

    /**
     * Data constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param Config $config
     * @param AttributeCollectionFactory $attributeSetCollection
     * @param CollectionFactory $collectionFactory
     * @param Action $action
     * @param Quote $quote
     * @param RapnetRepositoryInterface $rapnetRepository
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        Config $config,
        AttributeCollectionFactory $attributeSetCollection,
        CollectionFactory $collectionFactory,
        Action $action,
        Quote $quote,
        RapnetRepositoryInterface $rapnetRepository
    )
    {
        $this->logger = $logger;
        $this->rapnetRepository = $rapnetRepository;
        $this->_eavConfig = $config;
        $this->_attributeSetCollection = $attributeSetCollection;
        $this->_productCollection = $collectionFactory;
        $this->_updateAction = $action;
        $this->_quote = $quote;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isRapnet()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_RAPNET_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_USERNAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PASSWORD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getManufacturingDays()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_MANUFACTURING_DAYS,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getSiderColor()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SLIDER_COLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getHoverColor()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HOVER_COLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Shape Filter
     *
     * @return mixed
     */
    public function getShapeFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SHAPE_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Cut Filter
     *
     * @return mixed
     */
    public function getCutFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CUT_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Color Filter
     *
     * @return mixed
     */
    public function getColorFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_COLOR_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Claritry Filter
     *
     * @return mixed
     */
    public function getClarityFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CLARITY_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Certificate Filter
     *
     * @return mixed
     */
    public function getCertificateFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_CERTIFICATE_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Polish Filter
     *
     * @return mixed
     */
    public function getPolishFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_POLISH_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Symmetry Filter
     *
     * @return mixed
     */
    public function getSymmetryFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SYMMETRY_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Flourescence Filter
     *
     * @return mixed
     */
    public function getFlourescenceFilter()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_FLOURESCENCE_FILTER,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $id
     * @return \Codilar\Rapnet\Model\Rapnet
     */
    public function getDiamondById($id)
    {
        $diamondData = $this->getDiamondData($id);
        return $diamondData;
    }

    /**
     * @param $id
     * @return \Codilar\Rapnet\Model\Rapnet
     */
    public function getDiamondData($id)
    {
        try {
            $diamond = $this->rapnetRepository->load($id, 'diamond_id');
            return $diamond;
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function getAttributeSetId()
    {
        $productEntityId = $this->_eavConfig->getEntityType(\Magento\Catalog\Model\Product::ENTITY)->getId();
        /** @var  $attributeCollection Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection */
        $attributeCollection = $this->_attributeSetCollection->create()->addFieldToFilter('entity_type_id', $productEntityId)
            ->addFieldToFilter('attribute_set_name', self::DIAMOND_PRODUCT);
        $attributeSet = current($attributeCollection->getData());
        $attributeSetId = $attributeSet["attribute_set_id"];
        return $attributeSetId;
    }

    /**
     * @return bool
     */
    public function updateRapnetProductPrice()
    {

        $attributeSet = $this->getAttributeSetId();
        /** @var  $collection \Magento\Catalog\Model\ResourceModel\Product\Collection */
        $collection = $this->_productCollection->create()->addFieldToSelect("*")
            ->addFieldToFilter('attribute_set_id', $attributeSet);
        foreach ($collection as $product) {
            $diamondData = $this->getDiamondById($product->getSku());
            if ($diamondData) {
//                $diamondName = $diamondData->getDiamondCut() . " " . $diamondData->getDiamondShape() . " " . $diamondData->getDiamondColor() . " " . $diamondData->getDiamondClarity();
//                $this->_updateAction->updateAttributes(
//                    [$product->getId()],
//                    [
//                        'name' => $diamondName,
//                        'price' => $diamondData->getDiamondPrice(),
//                        'rapnet_diamond_shape' => $diamondData->getDiamondShape(),
//                        'rapnet_diamond_certificates' => $diamondData->getDiamondLab(),
//                        'rapnet_diamond_carats' => $diamondData->getDiamondCarats(),
//                        'rapnet_diamond_clarity' => $diamondData->getDiamondClarity(),
//                        'rapnet_diamond_color' => $diamondData->getDiamondColor(),
//                        'rapnet_diamond_cut' => $diamondData->getDiamondCut(),
//                        'rapnet_diamond_polish' => $diamondData->getDiamondPolish(),
//                        'rapnet_diamond_symmetry' => $diamondData->getDiamondSymmetry(),
//                        'rapnet_diamond_table' => $diamondData->getDiamondTablePercent(),
//                        'rapnet_diamond_depth' => $diamondData->getDiamondDepthPercent(),
//                        'rapnet_diamond_measurements' => $diamondData->getDiamondMeasurements(),
//                        'rapnet_diamond_fluorescence' => $diamondData->getDiamondFluoresence(),
//                        'rapnet_diamond_lab' => $diamondData->getDiamondLab(),
//                        'rapnet_diamond_certimg' => $diamondData->getDiamondCertificateNum()
//                    ],
//                    0
//                );
                $product->setPrice($diamondData->getDiamondPrice());
                $product->save();
            }

        }

    }
}
