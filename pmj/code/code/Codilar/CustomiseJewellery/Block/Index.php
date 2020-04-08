<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 30/11/18
 * Time: 12:46 PM
 */
namespace Codilar\CustomiseJewellery\Block;
use Codilar\Sms\Helper\Transport;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Index
 * @package Codilar\CustomiseJewellery\Block
 */
class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var CollectionFactory
     */
    protected $_productCollectionFactory;
    /**
     * @var ImageBuilder
     */
    protected $imageBuilder;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * @var Transport
     */
    protected $helper;
    /**
     * @var RequestInterface
     */
    protected $_request;

    /**
     * Index constructor.
     * @param Context $context
     * @param CollectionFactory $productCollectionFactory
     * @param ImageBuilder $imageBuilder
     * @param StoreManagerInterface $storeManager
     * @param UrlInterface $urlBuilder
     * @param Transport $helper
     * @param RequestInterface $request
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $productCollectionFactory,
        ImageBuilder $imageBuilder,
        StoreManagerInterface $storeManager,
        UrlInterface $urlBuilder,
        Transport $helper,
        RequestInterface $request,
        array $data = []
    )
    {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->imageBuilder=$imageBuilder;
        $this->urlBuilder = $urlBuilder;
        $this->helper=$helper;
        $this->_request=$request;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }

    /**
     * @param $product
     * @param $imageId
     * @param array $attributes
     * @return mixed
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        return $this->imageBuilder->setProduct($product)
            ->setImageId($imageId)
            ->setAttributes($attributes)
            ->create();
    }

    /**
     * @return Transport
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @return mixed
     */
    public function getSkuParameter()
    {
        return $this->_request->getParam('sku');
    }


}