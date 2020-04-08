<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 3/10/17
 * Time: 5:25 PM
 */

namespace Codilar\Offers\Block\Adminhtml\CmsBlock\Edit;


use Magento\Backend\Block\Template;
use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\ProductFactory;
use Codilar\Offers\Helper\Data as OfferHelper;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;

class AssignProducts extends Template
{
    /**
     * Block template
     *
     * @var string
     */
    protected $_template = 'block/edit/assign_products.phtml';


    /**
     * @var \Codilar\Offers\Block\Adminhtml\CmsBlock\Tab\Product
     */
    protected $blockGrid;

    /**
     * @var Registry
     */
    protected $registry;

    /**
     * @var EncoderInterface
     */
    protected $jsonEncoder;
    protected $productFactory;
    protected $offerHelper;

    /**
     * AssignProducts constructor.
     *
     * @param Context $context
     * @param Registry $registry
     * @param EncoderInterface $jsonEncoder
     * @param ProductFactory $productFactory
     * @param OfferHelper $offerHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        EncoderInterface $jsonEncoder,
        ProductFactory $productFactory,
        OfferHelper $offerHelper,
        array $data = []
    )
    {
        $this->registry = $registry;
        $this->jsonEncoder = $jsonEncoder;
        $this->productFactory = $productFactory;
        $this->offerHelper = $offerHelper;
        parent::__construct($context, $data);
    }

    /**
     * Retrieve instance of grid block
     *
     * @return \Magento\Framework\View\Element\BlockInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBlockGrid()
    {
        if (null === $this->blockGrid) {
            $this->blockGrid = $this->getLayout()->createBlock(
                'Codilar\Offers\Block\Adminhtml\CmsBlock\Tab\Product',
                'block.product.grid'
            );
        }
        return $this->blockGrid;
    }

    /**
     * Return HTML of grid block
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getBlockGrid()->toHtml();
    }

    /**
     * @return string
     */
    public function getProductsJson()
    {
        $products = [];
        $storeProducts = $this->offerHelper->getBlockProducts($this->getBlockId());
        foreach ($storeProducts as $storeProduct) {
            $products[$storeProduct] = 1;
        }

        if (!empty($products)) {
            return $this->jsonEncoder->encode($products);
        }

        return '{}';
    }

    /**
     *
     * @return array|null
     */
    /**
     * @return string|null
     */
    public function getBlockId()
    {
        return $this->getRequest()->getParam('id');
    }
}