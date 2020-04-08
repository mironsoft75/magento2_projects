<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Block\Product;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Evincemage\Rapnet\Helper\Data as Helper;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Catalog\Helper\Image as ImageHelper;

class View extends Template
{
    /**
     * @var string
     */
    protected $_template = 'product/view.phtml';

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var PriceHelper
     */
    protected $priceHelper;

    /**
     * @var product data
     */
    protected $product;

    /**
     * @var ImageHelper
     */
    protected $imageHelper;

    /**
     * View constructor.
     * @param Context $context
     * @param Helper $helper
     * @param PriceHelper $priceHelper
     * @param ImageHelper $imageHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Helper $helper,
        PriceHelper $priceHelper,
        ImageHelper $imageHelper,
        array $data = []
    )
    {
        $this->helper = $helper;
        $this->priceHelper = $priceHelper;
        $this->imageHelper = $imageHelper;
        parent::__construct($context);
    }

    /**
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function _prepareLayout()
    {
        // add Home breadcrumb
        if ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs')) {
            $breadcrumbs->addCrumb(
                'home',
                [
                    'label' => __('Home'),
                    'title' => __('Go to Home Page'),
                    'link' => $this->_storeManager->getStore()->getBaseUrl()
                ]
            )->addCrumb(
                'search',
                ['label' => __('Diamond')]
            );
        }
        return parent::_prepareLayout();
    }

    /**
     * @return string
     */
    public function getSubmitUrl()
    {
        return $this->getUrl('diamondsearch/cart/add', ['id'=>$this->product['diamond_id'],'_secure' => true]);
    }

    /**
     * @return array|product
     */
    public function getProduct()
    {
        $id = $this->getRequest()->getParam('id');

        if(!$this->product){
            $this->product = (array)$this->helper->getDiamondById($id);
        }
        return $this->product;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->product['size'] . " " .
            $this->product['shape'] . " " .
            $this->product['color'] . " " .
            $this->product['clarity'];
    }

    /**
     * @return float|string
     */
    public function getPrice()
    {
        return $this->priceHelper->currency($this->product['total_sales_price_in_currency'], true, false);;
    }

    /**
     * @return string
     */
    public function getPlaceholderUr()
    {
        return $this->imageHelper->getDefaultPlaceholderUrl('image');
    }

    /**
     * @return array
     */
    public function getProductAttributes()
    {
       return [
            'ID' => 'diamond_id',
            'Shape' => 'shape',
            'Carat' => 'size',
            'Certificate' => 'lab',
            'Color' => 'color',
            'Clarity' => 'clarity',
            'Cut' => 'cut',
            'Polish' => 'polish',
            'Symmetry' => 'symmetry',
            'Table' => 'table_percent',
            'Depth' => 'depth_percent',
            'Measurments' => 'meas_width',
            'Fluorescence' => 'fluor_intensity',
            'Comment' => 'No'
        ];
    }

}