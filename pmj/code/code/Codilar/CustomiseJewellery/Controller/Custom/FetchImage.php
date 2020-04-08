<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 4/12/18
 * Time: 4:32 PM
 */

namespace Codilar\CustomiseJewellery\Controller\Custom;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class FetchImage
 * @package Codilar\CustomiseJewellery\Controller\Custom
 */
class FetchImage extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;
    /**
     * @var \Magento\Catalog\Helper\ImageFactory
     */
    protected $_helperFactory;
    /**
     * @var
     */
    protected $_resultFactory;
    /**
     * @var \Magento\Catalog\Model\ProductFactory
     */
    protected $productFactory;
    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterfaceFactory
     */
    protected $_productRepositoryFactory;

    /**
     * FetchImage constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Catalog\Helper\ImageFactory $helperFactory
     * @param \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Magento\Catalog\Model\ProductFactory $productFactory,
        \Magento\Catalog\Helper\ImageFactory $helperFactory,
        \Magento\Catalog\Api\ProductRepositoryInterfaceFactory $productRepositoryFactory,
        \Magento\Customer\Model\Session $customerSession

    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_resultFactory = $context->getResultFactory();
        $this->productFactory = $productFactory;
        $this->_helperFactory = $helperFactory;
        $this->_productRepositoryFactory = $productRepositoryFactory;
        $this->customerSession = $customerSession;
        return parent::__construct($context);
    }

    public function execute()
    {
        $post = $this->getRequest()->getPost();
        $sku = $post['sku'];
        try {

            $product = $this->_productRepositoryFactory->create()->get($sku);
            $imageUrl = $this->getImage($product, 'product_base_image')->getUrl();
            $response = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
                ->setData([
                    'status' => "true",
                    'imageUrl' => $imageUrl
                ]);
            $this->customerSession->setCheckProductId($sku);
            $this->customerSession->setCheckSkuStatus("true");
            return $response;
        } catch (\Exception $e) {
            $response = $this->resultFactory
                ->create(\Magento\Framework\Controller\ResultFactory::TYPE_JSON)
                ->setData([
                    'status' => "false",
                    'message' => "Sku does not exist."
                ]);
            $this->customerSession->setCheckProductId($sku);
            $this->customerSession->setCheckSkuStatus("false");
            return $response;
        }

    }

    /**
     * @param $product
     * @param $imageId
     * @param array $attributes
     * @return mixed
     */
    public function getImage($product, $imageId, $attributes = [])
    {
        $image = $this->_helperFactory->create()->init($product, $imageId)
            ->constrainOnly(true)
            ->keepAspectRatio(true)
            ->keepTransparency(true)
            ->keepFrame(false)
            ->resize(200, 300);
        return $image;
    }
}