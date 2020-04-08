<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Codilar\Base\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Type;
use Evincemage\Rapnet\Helper\Data as Helper;
use Magento\Eav\Model\Entity\Attribute\Set;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Checkout\Model\Cart as CustomerCart;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\App\RequestInterface;

class Add extends \Evincemage\Rapnet\Controller\Cart\Add
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
        RequestInterface $_request
    )
    {
        $this->_assetRepo = $_assetRepo;
        $this->_request = $_request;
        parent::__construct($context, $resultPageFactory, $product, $attributeSet, $helper, $storeManager, $directoryList, $file, $cart);
    }

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
            $attributeSetId = $this->attributeSet->load(self::ATTRIBUTE_SET,'attribute_set_name')->getAttributeSetId();

            if(!$attributeSetId){
                $this->messageManager->addError(__('Invalid Attribute Set'));
                return $resultRedirect;
            }
            $productShapes = ['Round','Princess','Emerald','Radiant','Pear','Marquise','Oval','Asscher','Heart','Cushion Modified','Cushion Brilliant'];
            $diamondData = $this->helper->getDiamondById($id);
            $diamondShape = $diamondData->shape;
            $params = array('_secure' => $this->_request->isSecure());
            if(in_array($diamondShape,$productShapes)){
                if(strpos($diamondShape,"Cushion") === false){
                    $diamondData->image = $this->_assetRepo->getUrlWithParams('Evincemage_Rapnet::images/'.$diamondShape.'.jpg', $params);
                }
                else{
                    $diamondData->image = $this->_assetRepo->getUrlWithParams('Evincemage_Rapnet::images/Cushion.jpg', $params);
                }
            }
            if(!count($diamondData)){
                $this->messageManager->addError(__('Something want wrong. Please again after sometime.'));
                return $resultRedirect;
            }

            $diamondName = $diamondData->size . " " . $diamondData->shape . " " . $diamondData->color . " " . $diamondData->clarity;

            $productData = [
                'name' => $diamondName,
                'type_id'=>Type::TYPE_SIMPLE,
                //'sku' =>$diamondData->diamond_id.'-'.rand(10,9999),
                'sku' =>$diamondData->diamond_id,
                'attribute_set_id' => $attributeSetId,
                'weight' => 1,
                'visibility' =>\Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
                'status' => true,
                'tax_class_id' => 0,
                'price' => $diamondData->total_sales_price_in_currency,
                'website_ids' => [$this->_storeManager->getWebsite()->getId()],
                'stock_data' =>  [
                                    'use_config_manage_stock' => 0,
                                    'manage_stock' => 1,
                                    'is_in_stock' => 1,
                                    'qty' => 100000
                                ],
                'url_key'=>$diamondName.'-'.rand(10,9999),
                'rapnet_diamond_shape' => isset($diamondData->shape)?$diamondData->shape :'',
                'rapnet_diamond_certificates' => isset($diamondData->lab)?$diamondData->lab :'',
                'rapnet_diamond_carats' => isset($diamondData->size)? $diamondData->size :'',
                'rapnet_diamond_clarity' => isset($diamondData->clarity)? $diamondData->clarity  :'',
                'rapnet_diamond_color' => isset($diamondData->color)? $diamondData->color :'',
                'rapnet_diamond_cut' => isset($diamondData->cut)? $diamondData->cut :'',
                'rapnet_diamond_polish' => isset($diamondData->polish)? $diamondData->polish :'',
                'rapnet_diamond_symmetry' => isset($diamondData->symmetry)? $diamondData->symmetry :'',
                'rapnet_diamond_table' => isset($diamondData->table_percent)? $diamondData->table_percent :'',
                'rapnet_diamond_depth' => isset($diamondData->depth_percent)? $diamondData->depth_percent :'',
                'rapnet_diamond_measurements' => isset($diamondData->meas_width)? $diamondData->meas_width :'',
                'rapnet_diamond_fluorescence' => isset($diamondData->fluor_intensity)? $diamondData->fluor_intensity :'',
                'rapnet_diamond_lab' => isset($diamondData->lab)? $diamondData->lab :'',
                'rapnet_diamond_certimg' => isset($diamondData->cert_num)?$diamondData->cert_num : '',
            ];

            $product = $this->product;
            $product->setData($productData);

            if (isset($diamondData->image)) {
                preg_match('/<img(.*)src(.*)=(.*)"(.*)"/U', $diamondData->image, $result);
                $imageUrl = array_pop($result);

                if($imageUrl) {
                    $imageType = substr(strrchr($imageUrl, "."), 1);

                    $filename = md5($imageUrl . $diamondName) . '.' . $imageType;
                    $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
                    /** create folder if it is not exists */
                    $this->file->checkAndCreateFolder($tmpDir);
                    /** @var string $newFileName */

                    $localImageUrl = $tmpDir . DIRECTORY_SEPARATOR . $filename;
                    file_put_contents($localImageUrl, file_get_contents(trim($imageUrl)));
                    $product->addImageToMediaGallery($localImageUrl, array('image', 'small_image', 'thumbnail'), false, false);
                }
                elseif($diamondData->image){
                    $imageUrl = $diamondData->image;
                    $imageType = substr(strrchr($imageUrl, "."), 1);

                    $filename = md5($imageUrl . $diamondName) . '.' . $imageType;
                    $tmpDir = $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp';
                    /** create folder if it is not exists */
                    $this->file->checkAndCreateFolder($tmpDir);
                    /** @var string $newFileName */

                    $localImageUrl = $tmpDir . DIRECTORY_SEPARATOR . $filename;
                    file_put_contents($localImageUrl, file_get_contents(trim($imageUrl)));
                    $product->addImageToMediaGallery($localImageUrl, array('image', 'small_image', 'thumbnail'), false, false);
                }

            }

            $product->save();

            $this->cart->addProduct($product, ['product'=>$product->getId(),'qty'=>1]);
            $this->cart->save();

            if (!$this->cart->getQuote()->getHasError()) {
                $message = __(
                    'You added %1 to your shopping cart.',
                    $product->getName()
                );
                $this->messageManager->addSuccessMessage($message);
            }

            $this->_redirect("checkout/cart/");

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $resultRedirect;
        }

        $resultPage = $this->resultPageFactory->create();
        return $resultPage;
    }

}