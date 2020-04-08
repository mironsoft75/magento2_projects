<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 24/11/18
 * Time: 9:26 PM
 */

namespace Codilar\Videostore\Model;

use Codilar\Videostore\Api\Data\VideostoreCartInterface;
use Codilar\Videostore\Api\VideostoreCartRepositoryInterface;
use Codilar\Videostore\Model\ResourceModel\VideostoreCart;
use Codilar\Videostore\Model\ResourceModel\VideostoreCart\Collection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Model\ProductRepository;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data as  PriceHelper;

class VideostoreCartRepository implements VideostoreCartRepositoryInterface
{

    /**
     * @var ResourceModel\VideostoreCart
     */
    private $resourceModel;
    /**
     * @var VideostoreCartFactory
     */
    private $cartFactory;
    /**
     * @var Collection
     */
    private $cartCollection;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    private $session;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;
    /**
     * @var Configurable
     */
    protected $_configurableProductInstance;

    private $priceHelper;

    private $imageFactory;

    /**
     * VideostoreCartRepository constructor.
     * @param ResourceModel\VideostoreCart $resourceModel
     * @param VideostoreCartFactory $cartFactory
     * @param Collection $cartCollection
     * @param ProductRepository $productRepository
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\Customer\Model\Session $customerSession
     * @param Configurable $configurable
     * @param Image $imageFactory
     * @param PriceHelper $priceHelper
     */
    public function __construct(
        VideostoreCart $resourceModel,
         \Codilar\Videostore\Model\VideostoreCartFactory $cartFactory,
        Collection $cartCollection,
        ProductRepository $productRepository,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Customer\Model\Session $customerSession,
        Configurable $configurable,
        Image $imageFactory,
        PriceHelper $priceHelper
    )
    {
        $this->resourceModel = $resourceModel;
        $this->cartFactory = $cartFactory;
        $this->cartCollection = $cartCollection;
        $this->productRepository = $productRepository;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->_configurableProductInstance = $configurable;
        $this->imageFactory = $imageFactory;
        $this->priceHelper = $priceHelper;
    }

    /**
     * @param VideostoreCartInterface $product
     * @param bool $saveOptions
     * @return mixed
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(VideostoreCartInterface $product, $saveOptions = false)
    {
        try {
            $this->resourceModel->save($product);
        }  catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(__('Unable to save product'), $e);
        }
    }

    /**
     * @param int $productId
     * @param bool $editMode
     * @param int|null $storeId
     * @param bool $forceReload
     * @return mixed
     */
    public function getById($productId, $editMode = false, $storeId = null, $forceReload = false)
    {
        return $this->cartFactory->create()->load($productId, 'product_id');
    }

    /**
     * @param VideostoreCartInterface $product
     * @return mixed
     * @throws LocalizedException
     */
    public function delete(VideostoreCartInterface $product)
    {
        try{
            $this->resourceModel->delete($product);
        }catch (\Exception $exception){
            throw new LocalizedException(__("Error deleting Product with Id : ".$product->getId()));
        }
        return $this;
    }

    /**
     * @param int $id
     * @return mixed
     * @throws LocalizedException
     */
    public function deleteById($id)
    {
        $cartItem = $this->getById($id);
        try {
            $this->delete($cartItem);
        } catch (LocalizedException $e) {
            throw new LocalizedException(__("Error deleting Product with Id : ".$cartItem->getId()));
        }
        return true;
    }

    /**
     * @return mixed
     */
    public function getCollection()
    {
        return $this->cartCollection;
    }

    /**
     * @return array|mixed
     * @throws LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProducts()
    {
        $productIds = $this->getProductIds();
        $ids = $productIds;
        $products = [];
        $product = [];
        foreach ($ids as $id){
            try {
                if(count($this->_configurableProductInstance->getParentIdsByChild($id))){
                    $parentId = $this->_configurableProductInstance->getParentIdsByChild($id);
                    $parentProduct =  $this->productRepository->getById($parentId[0]);
                    $childProduct = $this->productRepository->getById($id);
                    $product["name"] = $parentProduct->getName();
                    $product["thumbnail"] = $this->getImageUrl($parentProduct);
                    $product["url"] = $parentProduct->getProductUrl();
                    $product['id'] = $id;
                    $product['price'] = $this->priceHelper->currency($childProduct->getFinalPrice(), true, false);
                    array_push($products, $product);
                }else{
                    $currentProduct = $this->productRepository->getById($id);
                    $product["name"] = $currentProduct->getName();
                    $product["thumbnail"] = $this->getImageUrl($currentProduct);
                    $product["url"] = $currentProduct->getProductUrl();
                    $product['id'] = $id;
                    $product['price'] = $this->priceHelper->currency($currentProduct->getFinalPrice(), true, false);
                    array_push($products, $product);
                }

            } catch (NoSuchEntityException $e) {
                throw new LocalizedException(__('Product does not exists'));
                break;
            }
        }
        return $products;

    }

    /**
     * @return mixed
     */
    public function getProductIds()
    {
        if($this->customerSession->isLoggedIn()){
            return $this->getCollection()
                ->addFieldToFilter('videostore_customer_id',$this->customerSession->getCustomerId())
                ->getColumnValues('product_id');
        }
        else{
            return $this->getCollection()
                ->addFieldToFilter('videostore_customer_session_id',$this->session->getSessionId())
                ->getColumnValues('product_id');
        }
    }

    /**
     * @return mixed
     * @throws LocalizedException
     */
    public function deleteProductsFromCart()
    {
        $products = $this->getCollection();
        foreach ($products as $product){
            try {
                $product->delete();
            } catch (\Exception $exception) {
                throw new LocalizedException(__("Error deleting cart products"));
            }
        }
    }
    protected function getImageUrl(\Magento\Catalog\Model\Product $product) {
        /** @var \Magento\Catalog\Helper\Image $image */
        $width = 500;
        $height = 500;
        $image = $this->imageFactory->init($product, "product_thumbnail_image")
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize($width, $height);
        return $image->getUrl();
    }

}