<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Helper;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magestore\Bannerslider\Model\Banner;
use Magestore\Bannerslider\Model\Slider;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;

class Data extends AbstractHelper
{
    const DEVICES = ['0' => 'Select Device', 1 => 'Desktop', 2 => 'Mobile'];
    const TYPES = ['0' => 'Select Type', 1 => 'Product', 2 => 'Category', 3 => 'Custom'];
    protected $banner;
    protected $slider;
    protected $storeManager;
    protected $blockFactory;
    protected $productFactory;
    protected $productVisibility;
    protected $image;
    protected $categoryFactory;
    protected $priceHelper;
    protected $configurable;
    protected $productRepository;
    protected $timezone;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var AttributeFactory
     */
    private $attributeFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param Banner $banner
     * @param Slider $slider
     * @param StoreManagerInterface $storeManager
     * @param BlockFactory $blockFactory
     * @param ProductFactory $productFactory
     * @param Visibility $productVisibility
     * @param Image $image
     * @param CategoryFactory $categoryFactory
     * @param PriceHelper $priceHelper
     * @param Configurable $configurable
     * @param ProductRepositoryInterface $productRepository
     * @param TimezoneInterface $timezone
     * @param CategoryRepositoryInterface $categoryRepository
     * @param AttributeFactory $attributeFactory
     */
    public function __construct(
        Context $context,
        Banner $banner,
        Slider $slider,
        StoreManagerInterface $storeManager,
        BlockFactory $blockFactory,
        ProductFactory $productFactory,
        Visibility $productVisibility,
        Image $image,
        CategoryFactory $categoryFactory,
        PriceHelper $priceHelper,
        Configurable $configurable,
        ProductRepositoryInterface $productRepository,
        TimezoneInterface $timezone,
        CategoryRepositoryInterface $categoryRepository,
        AttributeFactory $attributeFactory
    ) {
        parent::__construct($context);
        $this->banner = $banner;
        $this->slider = $slider;
        $this->storeManager = $storeManager;
        $this->blockFactory = $blockFactory->create();
        $this->productFactory = $productFactory->create();
        $this->productVisibility = $productVisibility;
        $this->image = $image;
        $this->categoryFactory = $categoryFactory->create();
        $this->priceHelper = $priceHelper;
        $this->configurable = $configurable;
        $this->productRepository = $productRepository;
        $this->timezone = $timezone;
        $this->categoryRepository = $categoryRepository;
        $this->attributeFactory = $attributeFactory;
    }

    /**
     * @param int $sliderId
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getBannersBySliderId($sliderId)
    {
        $now = $this->timezone->date();
        $banners = $this->banner->getCollection()
            ->addFieldToFilter('slider_id', ['in' => $sliderId])
            ->addFieldToFilter('start_time', ['lteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('end_time', ['gteq' => $now->format('Y-m-d H:i:s')])
            ->addFieldToFilter('status', 1);
        return $banners;
    }


    /**
     * @return \Magento\Framework\App\Config\ScopeConfigInterface
     */
    public function getScopeConfig(){
        return $this->scopeConfig;
    }


    /**
     * @param $sliderId
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getWebsiteOnlyBanners($sliderId)
    {
        $banners = $this->getBannersBySliderId($sliderId)
            ->addFieldToFilter('banner_device', 1);
        return $banners;
    }


    /**
     * @param $sliderId
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getMobileOnlyBanners($sliderId)
    {
        $banners = $this->getBannersBySliderId($sliderId)
            ->addFieldToFilter('banner_device', 2);
        return $banners;
    }

    /**
     * @param \Magestore\Bannerslider\Model\Banner $banner
     * @return string
     */
    public function getOnClickUrl($banner) {
        $url = "";
        try {
            if ($banner->getData('type') == 2) {
                if (!$banner->getData('category_id')) {
                    throw new LocalizedException(__("No category ID present in banner #".$banner->getId()));
                } else {
                    $categoryId = $banner->getData('category_id');
                    //$filter = $this->getFilterData($banner->getData('attribute_options'));
                    /* @var \Magento\Catalog\Model\Category $category */
                    $category = $this->categoryRepository->get($categoryId);
                    $url = $category->getUrl();//."?".$filter;
                }
            } else if($banner->getData('type') == 3) {
                $url = $banner->getData('click_url');
            } else  {
                if (!$banner->getData('product_id')) {
                    throw new LocalizedException(__("No product ID present in banner #".$banner->getId()));
                } else {
                    $productId = $banner->getData('product_id');
                    /* @var Product $product */
                    $product = $this->productRepository->getById($productId);
                    $url = $product->getProductUrl();
                }
            }
        } catch (\Exception $exception) {
            $this->_logger->critical($exception->getMessage());
            $url = "#";
        }
        return $url;
    }


    /**
     * @param string $options
     * @return string
     */
    public function getFilterData($options)
    {
        $filter = [];
        $data = json_decode($options, true);
        $i = 0;
        foreach ($data as $key => $item){
            $val = [];
            if ($key !== "price") {
                $attribute = $this->attributeFactory->create();
                $attribute->load($key);
                if ($attribute->getId()) {
                    $key = $attribute->getAttributeCode();
                }
            }
            foreach ($item as $value){
                $val[] = $value;
            }
            if ($key == "price" && count(explode("-", $val[0])) == 1) {
                $val[0] = "1-".$val[0];
            }
            $val = implode('-', $val);
            $filter[] = "$key=$val";
            $i ++;
        }
        $filters = implode('&', $filter);
        return $filters;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMediaBaseUrl()
    {
        return $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * @param null|string|string[] $categoryKey
     * @return \Magento\Framework\Data\Collection\AbstractDb
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getProductBlocks($categoryKey = null)
    {
        $collection = $this->blockFactory->getCollection()
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('has_product', 1)
            ->addFieldToFilter('store_id', ['in' => [
                "0", // All store views
                $this->storeManager->getStore()->getId()
            ]])
            ->addFieldToFilter('product_data', [['notnull' => true], 'neq' => '']);
        if ($categoryKey) {
            if (!is_array($categoryKey)) {
                $categoryKey = [$categoryKey];
            }
            $collection->addFieldToFilter('category_key', ['in' => $categoryKey]);
        }
        return $collection;
    }

    /**
     * @param array|string $productIds
     * @return mixed
     */
    public function getBlockProductsData($productIds)
    {
        $products = $this->productFactory->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('entity_id', ['in' => $productIds])
            ->addAttributeToFilter('status', 1);
        return $products;
    }

    /**
     * @param $product
     * @param $code
     * @return Image
     */
    public function getProductImage($product, $code)
    {
        return $this->image->init($product, $code)
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize(75, 75);
    }

    /**
     * @return mixed
     */
    public function getTopCategories()
    {
        $categories = $this->getCategories()
            ->addFieldToFilter('topcategory', 1)
            ->addFieldToSelect('*');
        return $categories;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function getTopLevelCategories()
    {
        $categories = $this->getCategories()
            ->addFieldToFilter('level',2)
            ->addFieldToFilter('include_in_mega_menu', 1)
            ->addFieldToSelect(['name', 'children_count', 'image', 'mobile_image', 'website_image', 'mobile_icon' ]);
        return $categories;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    public function getCategories()
    {
        $categories = $this->categoryFactory->getCollection()
            ->addFieldToFilter('entity_id', ['nin' => 2])
            ->addIsActiveFilter();
        return $categories;
    }

    /**
     * @param $price
     * @return float|string
     */
    public function getFormattedPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }

    /**
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getConfigurableAttributesData($product)
    {

        $parentProductId = $this->configurable->getParentIdsByChild($product->getId());
        $attrData = [];
        if ($parentProductId) {
            $parentProduct = $this->productRepository->getById($parentProductId[0]);
            $usedAttributes = $this->configurable->getConfigurableAttributesAsArray($parentProduct);
            foreach ($usedAttributes as $attribute) {
                $attr = $attribute['attribute_code'];
                $slectedValue = $product->getData($attr);
                $key = array_search($slectedValue, array_column($attribute['options'], 'value'));
                if ($key && array_key_exists($key, $attribute['options'])) {
                    $attrData[] = $attribute['options'][$key]['label'];
                }
            }
        }

        if ($product->getWeightUnitType()) {
            $attrData[] = $product->getWeight() . ' ' . $product->getWeightUnitType();
        }
        $product->setVariations($attrData);
        return $product;
    }
}
