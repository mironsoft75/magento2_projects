<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Codilar\Rapnet\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Codilar\Rapnet\Helper\Data as Helper;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\RequestInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product\Attribute\Repository as ProductAttribute;
use Magento\Eav\Model\Entity\AttributeFactory;
use Magento\Eav\Api\AttributeOptionManagementInterface;

/**
 * Class Add
 * @package Codilar\Rapnet\Controller\Cart
 */
class Add extends Action
{

    const ATTRIBUTE_SET = 'Diamond Product';

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

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
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Directory List
     *
     * @var DirectoryList
     */
    protected $directoryList;

    /**
     * File interface
     *
     * @var File
     */
    protected $file;

    /**
     * @var \Magento\Checkout\Model\Cart
     */
    protected $cart;
    /**
     * @var Repository
     */
    protected $_assetRepo;
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
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
     * Add constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Product $product
     * @param Set $attributeSet
     * @param Helper $helper
     * @param StoreManagerInterface $storeManager
     * @param DirectoryList $directoryList
     * @param File $file
     * @param CustomerCart $cart
     * @param Repository $_assetRepo
     * @param RequestInterface $_request
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Product $product,
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
        AttributeOptionManagementInterface $attributeOptionManagement
    )
    {
        $this->_assetRepo = $_assetRepo;
        $this->_request = $_request;
        $this->resultPageFactory = $resultPageFactory;
        $this->product = $product;
        $this->attributeSet = $attributeSet;
        $this->helper = $helper;
        $this->_storeManager = $storeManager;
        $this->directoryList = $directoryList;
        $this->file = $file;
        $this->cart = $cart;
        $this->productRepository = $productRepository;
        $this->_productAttribute = $attribute;
        $this->_eavAttributeFactory = $attributeFactory;
        $this->_attributeOptionManagement = $attributeOptionManagement;
        parent::__construct($context);
    }

    /**
     * @param $attributeName
     * @param $diamondData
     * @return string
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function addAttributeOptions($attributeName, $diamondData)
    {
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
        $attributes = $this->_productAttribute->get($attributeName)->getOptions();
        foreach ($attributes as $attributeOption) {
            if ($attributeOption->getValue() != '') {
                if ($attributeOption->getLabel() == $diamondData) {
                    return $attributeOption->getValue();
                }
            }
        }

    }

    /**
     * @param $attributeName
     * @param $diamondData
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function setAttributeValues($attributeName, $diamondData)
    {
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

        if (!$id) {
            $this->messageManager->addError(__('Invalid Request'));
            return $resultRedirect;
        }

        try {
            $product = $this->productRepository->get($id);
            $this->cart->addProduct($product, ['product' => $product->getId(), 'qty' => 1]);
            $this->cart->save();
            if (!$this->cart->getQuote()->getHasError()) {
                $message = __(
                    'You added %1 to your shopping cart.',
                    $product->getName()
                );
                $this->messageManager->addSuccessMessage($message);
            }
            return $this->_redirect("checkout/cart/");
        } catch (\Exception $e) {
            $attributeSetId = $this->attributeSet->load(self::ATTRIBUTE_SET, 'attribute_set_name')->getAttributeSetId();

            if (!$attributeSetId) {
                $this->messageManager->addError(__('Invalid Attribute Set'));
                return $resultRedirect;
            }
            $productShapes = ['Round', 'Princess', 'Emerald', 'Radiant', 'Pear', 'Marquise', 'Oval', 'Asscher', 'Heart', 'Cushion Modified', 'Cushion Brilliant'];
            $diamondData = $this->helper->getDiamondById($id);
            $diamondArrData = $diamondData->getData();
            $diamondShape = $diamondData->getDiamondShape();
            $params = ['_secure' => $this->_request->isSecure()];
            if (in_array($diamondShape, $productShapes)) {
                if (strpos($diamondShape, "Cushion") === false) {
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
                    'qty' => 1000
                ],
                'url_key' => $diamondName . '-' . rand(10, 9999),
                'rapnet_diamond_shape' => $this->setAttributeValues('rapnet_diamond_shape', $diamondArrData['diamond_shape']),
                'rapnet_diamond_certificates' => $this->setAttributeValues('rapnet_diamond_certificates', $diamondArrData['diamond_lab']),
                'rapnet_diamond_carats' => isset($diamondArrData['diamond_cut']) ? $diamondArrData['diamond_cut'] : '',
                'rapnet_diamond_clarity' => $this->setAttributeValues('rapnet_diamond_clarity', $diamondArrData['diamond_clarity']),
                'rapnet_diamond_color' => $this->setAttributeValues('rapnet_diamond_color', $diamondArrData['diamond_color']),
                'rapnet_diamond_cut' => $this->setAttributeValues('rapnet_diamond_cut', $diamondArrData['diamond_cut']),
                'rapnet_diamond_polish' => $this->setAttributeValues('rapnet_diamond_polish', $diamondArrData['diamond_polish']),
                'rapnet_diamond_symmetry' => $this->setAttributeValues('rapnet_diamond_symmetry', $diamondArrData['diamond_symmetry']),
                'rapnet_diamond_table' => isset($diamondArrData['diamond_table_percent']) ? $diamondArrData['diamond_table_percent'] : '',
                'rapnet_diamond_depth' => isset($diamondArrData['diamond_depth_percent']) ? $diamondArrData['diamond_depth_percent'] : '',
                'rapnet_diamond_measurements' => isset($diamondArrData['diamond_meas_width']) ? $diamondArrData['diamond_meas_width'] : '',
                'rapnet_diamond_fluorescence' => $this->setAttributeValues('rapnet_diamond_fluorescence', $diamondArrData['diamond_fluoresence']),
                'rapnet_diamond_lab' => isset($diamondArrData['diamond_lab']) ? $diamondArrData['diamond_lab'] : '',
                'rapnet_diamond_certimg' => isset($diamondArrData['diamond_certificate_num']) ? $diamondArrData['diamond_certificate_num'] : '',
            ];

            $product = $this->product;
            $product->setData($productData);

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
                    $product->addImageToMediaGallery($localImageUrl, ['image', 'small_image', 'thumbnail'], false, false);
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
                    $product->addImageToMediaGallery($localImageUrl, ['image', 'small_image', 'thumbnail'], false, false);
                }
            }

            $product->save();

            $this->cart->addProduct($product, ['product' => $product->getId(), 'qty' => 1]);
            $this->cart->save();

            if (!$this->cart->getQuote()->getHasError()) {
                $message = __(
                    'You added %1 to your shopping cart.',
                    $product->getName()
                );
                $this->messageManager->addSuccessMessage($message);
            }

            return $this->_redirect("checkout/cart/");
        }
    }
}
