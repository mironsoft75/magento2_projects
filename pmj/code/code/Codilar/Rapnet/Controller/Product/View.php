<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Codilar\Rapnet\Controller\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Codilar\Rapnet\Helper\Data as Helper;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttribute;
use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;
use Psr\Log\LoggerInterface;


/**
 * Class View
 * @package Codilar\Base\Controller\Product
 */
class View extends Action
{
    /**
     *  Attribute Set
     */
    const ATTRIBUTE_SET = 'Diamond Product';
    /**
     * Diamond Carats
     */
    const DIAMOND_CARATS = 'diamond_carats';
    /**
     * Diamond Polish
     */
    const DIAMOND_POLISH = 'diamond_polish';
    /**
     * Diamond Clarity
     */
    const DIAMOND_CLARITY = 'diamond_clarity';
    /**
     * Diamond Color
     */
    const DIAMOND_COLOR = 'diamond_color';
    /**
     * Diamond Shape
     */
    const DIAMOND_SHAPE = 'diamond_shape';
    /**
     * Diamond Lab
     */
    const DIAMOND_LAB = 'diamond_lab';
    /**
     * Cushion
     */
    const CUSHION = 'Cushion';
    /**
     * Value
     */
    const VALUE = "value";
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Product
     */
    protected $product;
    /**
     * @var Set
     */
    protected $attributeSet;

    /**
     * @var Helper
     */
    protected $helper;
    /**
     * @var Repository
     */
    protected $_assetRepo;
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var ProductAttribute
     */
    protected $_productAttribute;
    /**
     * @var AttributeFactory
     */
    protected $_eavAttributeFactory;
    /**
     * @var AttributeOptionManagementInterface
     */
    protected $_attributeOptionManagement;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @var DirectoryList
     */
    protected $directoryList;
    /**
     * @var File
     */
    protected $file;
    /**
     * @var CustomerCart
     */
    protected $cart;

    /**
     * View constructor.
     * @param Product $product
     * @param Context $context
     * @param Set $attributeSet
     * @param Helper $helper
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param File $file
     * @param CustomerCart $cart
     * @param Repository $_assetRepo
     * @param RequestInterface $_request
     * @param ProductRepositoryInterface $productRepository
     * @param ProductAttribute $attribute
     * @param AttributeFactory $attributeFactory
     * @param LoggerInterface $logger
     * @param AttributeOptionManagementInterface $attributeOptionManagement
     */
    public function __construct(
        Product $product,
        Context $context,
        Set $attributeSet,
        Helper $helper,
        StoreManagerInterface $storeManager,
        DirectoryList $directoryList,
        File $file,
        CustomerCart $cart,
        Repository $_assetRepo,
        RequestInterface $_request,
        ProductRepositoryInterface $productRepository,
        ProductAttribute $attribute,
        AttributeFactory $attributeFactory,
        LoggerInterface $logger,
        AttributeOptionManagementInterface $attributeOptionManagement
    )
    {
        parent::__construct($context);
        $this->productRepository = $productRepository;
        $this->product = $product;
        $this->attributeSet = $attributeSet;
        $this->helper = $helper;
        $this->_storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->cart = $cart;
        $this->_assetRepo = $_assetRepo;
        $this->_request = $_request;
        $this->_productAttribute = $attribute;
        $this->_eavAttributeFactory = $attributeFactory;
        $this->_attributeOptionManagement = $attributeOptionManagement;
        $this->_logger = $logger;
    }

    /**
     * @param $attributeName
     * @param $diamondData
     * @return string
     */
    public function addAttributeOptions($attributeName, $diamondData)
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
            $attributeOptions = [$diamondData];
            $existingMagentoAttributeOptions = [];
            $newOptions = [];
            $counter = 0;
            $input = preg_quote(self::CUSHION, '~');
            $cushion = preg_grep('~' . $input . '~', $attributeOptions);
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
                if ($label == self::CUSHION) {
                    $cushionValue = $option->getValue();
                }
                $existingMagentoAttributeOptions[] = $label;
                $newOptions[self::VALUE][$option->getValue()] = [$label, $label];
                $counter++;
            }

            foreach ($attributeOptions as $option) {
                if ($option == '') {
                    continue;
                }
                if (in_array($option, $cushion)) {
                    $label = self::CUSHION;
                    $existingMagentoAttributeOptions[] = $option;
                    $newOptions[self::VALUE][$cushionValue] = [$label, $label];
                    return $this->setNewAttributeValues($attributeName, $label);
                }
                if (!in_array($option, $existingMagentoAttributeOptions)) {
                    $newOptions[self::VALUE]['option_' . $counter] = [$option, $option];
                }

                $counter++;
            }

            if (count($newOptions)) {
                $magentoAttribute->setOption($newOptions)->save();
            }
            return $this->setNewAttributeValues($attributeName, $diamondData);

        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $attributeName
     * @param $diamondData
     * @return string
     */
    public function setNewAttributeValues($attributeName, $diamondData)
    {
        try {
            $attributes = $this->_productAttribute->get($attributeName)->getOptions();
            foreach ($attributes as $attributeOption) {
                if ($attributeOption->getValue() != '' && $attributeOption->getLabel() == $diamondData) {
                    return $attributeOption->getValue();
                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $attributeName
     * @param $diamondData
     * @return string
     */
    public function setAttributeValues($attributeName, $diamondData)
    {
        try {
            $sizes = $this->_productAttribute->get($attributeName)->getOptions();
            foreach ($sizes as $sizesOption) {
                if ($sizesOption->getValue() != '') {
                    if ($sizesOption->getLabel() == $diamondData) {
                        return $sizesOption->getValue();
                    } else {
                        return $this->addAttributeOptions($attributeName, $diamondData);
                    }

                }
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $diamondArrData
     * @return string
     */
    public function createProductDescription($diamondArrData)
    {
        try {
            $description = "This";
            if ($diamondArrData[self::DIAMOND_CARATS]) {
                $description = $description . ' ' . $diamondArrData[self::DIAMOND_CARATS] . ' Carat';
            }
            if ($diamondArrData[self::DIAMOND_SHAPE]) {
                $description = $description . ' ' . $diamondArrData[self::DIAMOND_SHAPE];
            }
            if ($diamondArrData[self::DIAMOND_COLOR]) {
                $description = $description . ' ' . 'diamond ' . $diamondArrData[self::DIAMOND_COLOR] . ' Color';
            }
            if ($diamondArrData[self::DIAMOND_CLARITY]) {
                $description = $description . ' ' . $diamondArrData[self::DIAMOND_CLARITY] . ' Clarity';
            }
            if ($diamondArrData[self::DIAMOND_POLISH]) {
                $description = $description . ' has ' . $diamondArrData[self::DIAMOND_POLISH] . ' Proportions';
            }
            if ($diamondArrData[self::DIAMOND_LAB]) {
                $description = $description . ' ' . 'and a diamond grading report from ' . $diamondArrData[self::DIAMOND_LAB];
            }
            return $description;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $attributeSetId
     * @param $resultRedirect
     * @return mixed
     */
    public function checkattributeSetId($attributeSetId, $resultRedirect)
    {
        try {
            if (!$attributeSetId) {
                $this->messageManager->addError(__('Invalid Attribute Set'));
                return $resultRedirect;
            }
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $diamondArrData
     * @return string
     */
    public function createProductMeasurements($diamondArrData)
    {
        try {
            if ($diamondArrData['diamond_meas_width']) {
                if ($diamondArrData['diamond_table_percent'] && $diamondArrData['diamond_depth_percent']) {
                    $measurement = $diamondArrData['diamond_meas_width'] . '*' . $diamondArrData['diamond_table_percent'] . '*' . $diamondArrData['diamond_depth_percent'];
                } else {
                    $measurement = '-';
                }
            } else {
                $measurement = '-';
            }
            return $measurement;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('id');
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setRefererOrBaseUrl();
        try {
            $currentProduct = $this->productRepository->get($id);
            return $resultRedirect->setPath($currentProduct->getProductUrl());
        } catch (\Exception $e) {
            $attributeSetId = $this->attributeSet->load(self::ATTRIBUTE_SET, 'attribute_set_name')->getAttributeSetId();
            $this->checkattributeSetId($attributeSetId, $resultRedirect);

            $productShapes = ['Round', 'Princess', 'Emerald', 'Radiant', 'Pear', 'Marquise', 'Oval', 'Asscher', 'Heart', 'Cushion Modified', 'Cushion Brilliant'];
            $diamondData = $this->helper->getDiamondById($id);
            $diamondArrData = $diamondData->getData();
            $diamondShape = $diamondData->getDiamondShape();
            $params = ['_secure' => $this->_request->isSecure()];
            if (in_array($diamondShape, $productShapes)) {
                if (strpos($diamondShape, self::CUSHION) === false) {
                    $diamondData->image = $this->_assetRepo->getUrlWithParams('Codilar_Rapnet::images/' . $diamondShape . '.jpg', $params);
                } else {
                    $diamondData->image = $this->_assetRepo->getUrlWithParams('Codilar_Rapnet::images/Cushion.jpg', $params);
                }
            }

            if (!count($diamondData)) {
                $this->messageManager->addError(__('Something want wrong. Please again after sometime.'));
                return $resultRedirect;
            }

            $diamondName = $diamondData->getDiamondCut() . " " . $diamondData->getDiamondShape() . " " . $diamondData->getDiamondColor() . " " . $diamondData->getDiamondClarity();
            $newProduct = $this->product;
            $productData = [
                'name' => $diamondName,
                'type_id' => Type::TYPE_SIMPLE,
                'sku' => $id,
                'attribute_set_id' => $attributeSetId,
                'weight' => 1,
                'visibility' => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
                'status' => true,
                'price' => $diamondData->getDiamondPrice(),
                'website_ids' => [$this->_storeManager->getWebsite()->getId()],
                'stock_data' => [
                    'use_config_manage_stock' => 0,
                    'manage_stock' => 1,
                    'is_in_stock' => 1,
                    'max_sale_qty' => 1,
                    'qty' => 100
                ],
                'url_key' => $diamondName . '-' . rand(10, 9999),
                'rapnet_diamond_shape' => $this->setAttributeValues('rapnet_diamond_shape', $diamondArrData[self::DIAMOND_SHAPE]),
                'rapnet_diamond_certificates' => $this->setAttributeValues('rapnet_diamond_certificates', $diamondArrData[self::DIAMOND_LAB]),
                'rapnet_diamond_carats' => isset($diamondArrData[self::DIAMOND_CARATS]) ? $diamondArrData[self::DIAMOND_CARATS] : '',
                'rapnet_diamond_clarity' => $this->setAttributeValues('rapnet_diamond_clarity', $diamondArrData[self::DIAMOND_CLARITY]),
                'rapnet_diamond_color' => $this->setAttributeValues('rapnet_diamond_color', $diamondArrData[self::DIAMOND_COLOR]),
                'rapnet_diamond_cut' => $this->setAttributeValues('rapnet_diamond_cut', $diamondArrData['diamond_cut']),
                'rapnet_diamond_polish' => $this->setAttributeValues('rapnet_diamond_polish', $diamondArrData[self::DIAMOND_POLISH]),
                'rapnet_diamond_symmetry' => $this->setAttributeValues('rapnet_diamond_symmetry', $diamondArrData['diamond_symmetry']),
                'rapnet_diamond_table' => isset($diamondArrData['diamond_table_percent']) ? $diamondArrData['diamond_table_percent'] : '',
                'rapnet_diamond_depth' => isset($diamondArrData['diamond_depth_percent']) ? $diamondArrData['diamond_depth_percent'] : '',
                'rapnet_diamond_fluorescence' => $this->setAttributeValues('rapnet_diamond_fluorescence', $diamondArrData['diamond_fluoresence']),
                'rapnet_diamond_lab' => isset($diamondArrData[self::DIAMOND_LAB]) ? $diamondArrData[self::DIAMOND_LAB] : '',
                'rapnet_diamond_certimg' => isset($diamondArrData['diamond_certificate_num']) ? $diamondArrData['diamond_certificate_num'] : '',
            ];
            $description = $this->createProductDescription($diamondArrData);
            $productData['description'] = $description;
            $productData['short_description'] = $description;
            $measurement = $this->createProductMeasurements($diamondArrData);
            $productData['rapnet_diamond_measurements'] = $measurement;
            $productData['tax_class_id'] = $this->setAttributeValues('tax_class_id', 'Rapnet');
            $productData['manufacturing_days'] = $this->helper->getManufacturingDays();
            $newProduct->setData($productData);
            if (isset($diamondData->image)) {
                preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $diamondData->image, $result);
                $imageUrl = array_pop($result);
                if ($imageUrl) {
                    $imageType = substr(strrchr($imageUrl, "."), 1);

                    $filename = md5($imageUrl . $diamondName) . '.' . $imageType;
                    $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
                    /** create folder if it is not exists */
                    $this->file->checkAndCreateFolder($tmpDir);
                    /** @var string $newFileName */

                    $localImageUrl = $tmpDir . DIRECTORY_SEPARATOR . $filename;
                    $arrContextOptions = array(
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ),
                    );
                    file_put_contents($localImageUrl, file_get_contents(trim($imageUrl), false, stream_context_create($arrContextOptions)));
                    $newProduct->addImageToMediaGallery($localImageUrl, ['image', 'small_image', 'thumbnail'], false, false);
                } elseif ($diamondData->image) {
                    $imageUrl = $diamondData->image;
                    $imageType = substr(strrchr($imageUrl, "."), 1);

                    $filename = md5($imageUrl . $diamondName) . '.' . $imageType;
                    $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
                    /** create folder if it is not exists */
                    $this->file->checkAndCreateFolder($tmpDir);
                    /** @var string $newFileName */

                    $localImageUrl = $tmpDir . DIRECTORY_SEPARATOR . $filename;
                    $arrContextOptions = array(
                        "ssl" => array(
                            "verify_peer" => false,
                            "verify_peer_name" => false,
                        ),
                    );

                    file_put_contents($localImageUrl, file_get_contents(trim($imageUrl), false, stream_context_create($arrContextOptions)));
                    $newProduct->addImageToMediaGallery($localImageUrl, ['image', 'small_image', 'thumbnail'], false, false);
                }
            }
            $newProduct->save();
            return $resultRedirect->setPath($newProduct->getProductUrl());
        }
    }
}