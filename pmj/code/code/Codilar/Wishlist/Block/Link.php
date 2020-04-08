<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 28/1/19
 * Time: 7:29 PM
 */

namespace Codilar\Wishlist\Block;


class Link extends \Magento\Wishlist\Block\Link
{
    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Wishlist\Helper\Data $wishlistHelper, array $data = [])
    {
        $this->_template = 'Codilar_Wishlist::link.phtml';
        parent::__construct($context, $wishlistHelper, $data);
    }
}