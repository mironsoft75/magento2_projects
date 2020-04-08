<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 3/5/19
 * Time: 12:55 PM
 */

namespace Codilar\Base\Model\Product;

/**
 * Class Image
 * @package Codilar\Base\Model\Product
 */
class Image extends \Magento\Catalog\Model\Product\Image
{
    protected function _construct
    (
    ) {
        $this->_quality = 100;
        parent::_construct();
    }
}