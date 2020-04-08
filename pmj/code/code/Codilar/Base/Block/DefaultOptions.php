<?php

namespace Codilar\Base\Block;

use Magento\Framework\Json\Helper\Data;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\Template;

/**
 * Class DefaultOptions
 * @package Codilar\Base\Block
 */
class DefaultOptions extends Template
{
    /**
     * @var Registry
     */
    protected $_registry;
    /**
     * @var Data
     */
    protected $_jsonHelper;
    /**
     * @var UrlInterface
     */
    private $url;

    /**
     * DefaultOptions constructor.
     * @param Template\Context $context
     * @param Registry $registry
     * @param Data $jsonHelper
     * @param UrlInterface $url
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        Data $jsonHelper,
        UrlInterface $url,
        array $data = []
    )
    {
        $this->_registry = $registry;
        $this->_jsonHelper = $jsonHelper;
        parent::__construct($context, $data);
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getProductDataJson()
    {
        return $this->_jsonHelper->jsonEncode($this->_registry->registry('codilar_configurable_products_data'));
    }

    /**
     * @return string
     */
    public function getDiamondDetailsUrl(){
        return $this->url->getUrl('codilar_base/product/diamonddetails');
    }

    /**
     * @return string
     */
    public function getPriceBreakUpDetailsUrl(){
        return $this->url->getUrl('codilar_base/product/pricebreakup');
    }

    /**
     * @return bool
     */
    public function getProductTypeId()
    {
        $type = $this->_registry->registry('codilar_configurable_product_type');
        if ($type == "configurable") {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public function getDefaultOptionsJson()
    {
        $defaults = $this->_registry->registry('codilar_configurable_products_default');
        if ($defaults) {
            return $this->_jsonHelper->jsonEncode($defaults);
        }
        return "{}";
    }
}