<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 30/11/18
 * Time: 10:40 AM
 */

namespace Codilar\Videostore\Block\Email;


use Codilar\Videostore\Api\VideostoreCartRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Helper\Image;
use Magento\Framework\Pricing\Helper\Data as  PriceHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

class ProductList extends Template
{
    /**
     * @var VideostoreCartRepositoryInterface
     */
    protected $videostoreCartRepository;

    private $imageFactory;

    private $priceHelper;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var Configurable
     */
    protected $_configurableProductInstance;

    /**
     * ProductList constructor.
     * @param Template\Context $context
     * @param VideostoreCartRepositoryInterface $videostoreCartRepository
     * @param Image $imageFactory
     * @param PriceHelper $priceHelper
     * @param ProductRepositoryInterface $productRepository
     * @param Configurable $configurable
     * @param array $data
     */
    public function __construct(Template\Context $context,
                                VideostoreCartRepositoryInterface $videostoreCartRepository,
                                Image $imageFactory,
                                PriceHelper $priceHelper,
                                ProductRepositoryInterface $productRepository,
                                Configurable $configurable,
                                array $data = [])
    {
        parent::__construct($context, $data);
        $this->imageFactory = $imageFactory;
        $this->priceHelper = $priceHelper;
        $this->videostoreCartRepository = $videostoreCartRepository;
        $this->productRepository = $productRepository;
        $this->_configurableProductInstance = $configurable;
    }

    public function getProducts(){

        return $this->videostoreCartRepository->getProducts();
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

    /**
     * @param $productIds
     * @return array
     * @throws LocalizedException
     */
    public function getProductsByIds($productIds)
    {
        $ids = explode(',', $productIds);
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
                    $product['price'] = $this->priceHelper->currency($childProduct->getFinalPrice(), true, false);
                    array_push($products, $product);
                }else{
                        $currentProduct = $this->productRepository->getById($id);
                        $product["name"] = $currentProduct->getName();
                        $product["thumbnail"] = $this->getImageUrl($currentProduct);
                        $product["url"] = $currentProduct->getProductUrl();
                        $product['id'] = $currentProduct->getId();
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
}
