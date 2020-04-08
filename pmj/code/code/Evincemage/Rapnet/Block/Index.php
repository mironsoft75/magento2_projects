<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Block;

use Magento\Framework\View\Element\Template\Context;
use Evincemage\Rapnet\Helper\Data as Helper;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class Index extends Template
{
    /**
     * @var string
     */
    protected $_template = 'search.phtml';

    /**
     * @var Helper
     */
    protected $helper;

    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;


    /**
     * Attribute collection factory
     *
     * @var AttributeCollectionFactory
     */
    protected $_attributeCollectionFactory;

    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Index constructor.
     * @param Context $context
     * @param Helper $helper
     * @param SessionManagerInterface $sessionManager
     * @param ProductFactory $productFactory
     * @param StoreManagerInterface $storeManager
     * @param AttributeCollectionFactory $attributeCollectionFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        Helper $helper,
        SessionManagerInterface $sessionManager,
        ProductFactory $productFactory,
        StoreManagerInterface $storeManager,
        AttributeCollectionFactory $attributeCollectionFactory,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->helper = $helper;
        $this->sessionManager = $sessionManager;
        $this->_productFactory = $productFactory;
        $this->_storeManager = $storeManager;
        $this->_attributeCollectionFactory = $attributeCollectionFactory;
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
                ['label' => __('Search Diamonds')]
            );
        }
        return parent::_prepareLayout();
    }

    /**
     * @return bool
     */
    public function isRapnetEnable()
    {
        return $this->helper->isRapnet();
    }

    /**
     * @return string
     */
    public function getFormAction()
    {
        if ($this->isRapnetEnable()) {
            return $this->getUrl('diamondsearch/index/rapnetsearch', ['_secure' => true]);
        }
        return $this->getUrl('diamondsearch/index/search', ['_secure' => true]);
    }

    /**
     * @return mixed
     */
    public function getSiderColor()
    {
        return $this->helper->getSiderColor();
    }

    /**
     * @return mixed
     */
    public function getHoverColor()
    {
        return $this->helper->getHoverColor();
    }

    /**
     * @return SessionManagerInterface
     */
    public function getSession()
    {
        return $this->sessionManager;
    }

    /**
     * @return array
     */
    public function getDiamondAttributes()
    {
        $attributeData = array();
        $attributes = $this->getAttributes();

        foreach ($attributes as $attribute) {

            $code = $attribute->getAttributeCode();

            $attributeData[$code] = [
                'name' => $this->getAttributeLabel($attribute),
                'options' => $this->getCleanOptions($attribute->getSource()->getAllOptions(false), $code),
            ];
        }

        return $attributeData;

    }

    /**
     * @return mixed
     */
    public function getAttributes()
    {
        $attributes = $this->getData('attributes');
        if ($attributes === null) {
            $product = $this->_productFactory->create();
            $attributes = $this->_attributeCollectionFactory
                ->create()
                ->addStoreLabel($this->_storeManager->getStore()->getId())
                ->setOrder('main_table.attribute_id', 'asc')
                ->load();
            foreach ($attributes as $attribute) {
                $attribute->setEntity($product->getResource());
            }
            $this->setData('attributes', $attributes);
        }
        return $attributes;
    }

    /**
     * @param $allOptions
     * @param $code
     * @return array
     */
    public function getCleanOptions($allOptions, $code)
    {

        $options = array();
        $isSlider = false;
        $numerical = false;
        $request = $this->getRequest()->getParams();

        if ($code == 'rapnet_diamond_carats') {

            for ($i = 0.18; $i <= 10.0; $i += .1){
                $allOptions[] = [
                    'value' => $i,
                    'label' => $i
                ];
            }

            $numerical = array(0.18, 10.0);

            if (isset($request['diamond_carats'])) {

                if ($request['diamond_carats']['from'] != 0.18) {
                    $numerical[0] = $request['diamond_carats']['from'];
                }
                if ($request['diamond_carats']['to'] != 0.18) {
                    $numerical[1] = $request['diamond_carats']['to'];
                }
            }
        } else if ($code == 'price') {

            for ($i = 0; $i <= 5000000; $i += 500) {

                $allOptions[] = [
                    'value' => $i,
                    'label' => $i
                ];
            }

            $numerical = array(0, 5000000);

            if (isset($request['price'])) {

                if ($request['price']['from'] != 0) {
                    $numerical[0] = $request['price']['from'];
                }
                if ($request['price']['to'] != 5000000) {
                    $numerical[1] = $request['price']['to'];
                }
            }
        } else if ($code != 'rapnet_diamond_shape' &&
                   $code != 'rapnet_diamond_certificates' &&
                   $code != "rapnet_diamond_fancycolor") {

            $isSlider = true;
        }

        if ($numerical) {

            foreach ($numerical as $rangeId) {

                $arrangedOptions = [];

                foreach ($allOptions as $_option) {
                    $arrangedOptions[] = [
                        'value' => $_option['value'],
                        'label' =>  $_option['label'],
                        'selected' => $this->isSelected($code, $_option['value'], $isSlider, $numerical, $rangeId)
                    ];
                }
                $options[] = $arrangedOptions;
            }
        }else {

            foreach ($allOptions as $_option) {

                $label = $_option['label'];

                if ($label == "Poor"  ||
                    $label == "Ideal" ||
                    $label == "FCY"   ||
                    $label == "N"     ||
                    $label == "FL"    ||
                    $label == "I2"    ||
                    $label == "I3"    ||
                    $label == "Trillion") {

                    continue;
                } else {
                    $option = [
                        'value' => $_option['value'],
                        'label' => $label,
                        'selected' => $this->isSelected($code, $_option['value'], $isSlider, $numerical, $numerical)
                    ];
                }

                $options[] = $option;
            }
        }
        return $options;
    }

    /**
     * @param $code
     * @param $optionId
     * @param bool $slider
     * @param bool $numerical
     * @param bool $rangeId
     * @return string
     */
    public function isSelected($code, $optionId, $slider = false, $numerical = false, $rangeId = false)
    {
        $optionSelected = '';
        $code = substr($code, 7);
        $request = $this->getRequest()->getParams();

        if (isset($request[$code])) {
            if (!empty($request[$code])) {
                foreach ($request[$code] as $getId) {

                    if ($numerical) {
                        if (abs($rangeId - $optionId) < 0.00001) {
                            $optionSelected = 'selected';
                        }
                    } else {
                        if ($optionId == $getId) {
                            $optionSelected = 'selected';
                        }
                    }
                }
            }
        }elseif ($slider) {
            $optionSelected = 'selected';
        }else if ($numerical) {
            if (abs($rangeId - $optionId) < 0.00001) {
                $optionSelected = 'selected';
            }
        }

        return $optionSelected;
    }

}