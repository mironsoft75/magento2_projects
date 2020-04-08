<?php

/**
 * @package     Igorludgero_Socialshare
 * @author      Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @copyright   Igor Ludgero Miura - https://www.igorludgero.com/ - igor@igorludgero.com
 * @license     https://opensource.org/licenses/AFL-3.0  Academic Free License 3.0 | Open Source Initiative
 */

namespace Igorludgero\Socialshare\Block\Product\View;

class SocialLinks extends \Magento\Catalog\Block\Product\View\AbstractView {

    protected $_product;
    protected $_storeScope;

    public function __construct(\Magento\Catalog\Block\Product\Context $context, \Magento\Framework\Stdlib\ArrayUtils $arrayUtils, array $data = [])
    {
        parent::__construct($context, $arrayUtils, $data);
        $this->_product = $this->getProduct();
        $this->_storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
    }

    public function isEnabled($socialNetwork){
        $stringNetwork = "socialshare/$socialNetwork/active";
        return $this->_scopeConfig->getValue($stringNetwork ,$this->_storeScope);
    }

    public function getSortOrder($socialNetwork){
        $stringNetwork = "socialshare/$socialNetwork/order";
        return $this->_scopeConfig->getValue($stringNetwork,$this->_storeScope);
    }

    public function getEncodedUrl(){
        return urlencode($this->getUrl());
    }

    public function getEncodedName(){
        return urlencode($this->getName());
    }

    public function getName(){
        return $this->_product->getName();
    }

    public function getProductImage(){
        return $this->getUrl('pub/media/catalog').'product'.$this->_product->getImage();
    }

    public function getEncodedProductImage(){
        return urlencode($this->getProductImage());
    }   
    public function getCurrentProductUrl(){
        return $this->_product->getProductUrl();
    }

}