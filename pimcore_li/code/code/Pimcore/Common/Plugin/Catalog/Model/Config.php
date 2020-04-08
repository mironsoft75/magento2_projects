<?php
namespace Pimcore\Common\Plugin\Catalog\Model;

use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Config
 * @package Pimcore\Common\Plugin\Catalog\Model
 */
class Config  {

    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Config constructor.
     * @param StoreManagerInterface $storeManager
     *
     */
    public function __construct(
        StoreManagerInterface $storeManager
    ) {
        $this->_storeManager = $storeManager;
    }

    /**
     * @param \Magento\Catalog\Model\Config $catalogConfig
     * @param $options
     * @return array
     */
    public function afterGetAttributeUsedForSortByArray(\Magento\Catalog\Model\Config $catalogConfig, $options)
    {
        unset($options['name']);
        unset($options['price']);

        //Changing label
        $options['position'] = __("Most Popular");
        //New sorting options
        $customOption['best_seller'] = __("Best Seller");
        $customOption['price_asc'] = __("Price – Lowest to Highest");
        $customOption['price_desc'] = __("Price – Highest to Lowest");
        $customOption['name_asc'] = __("Product Name (A-Z)");
        $customOption['name_desc'] = __("Product Name (Z-A)");

        //Merge default sorting options with custom options
        $options = array_merge($options, $customOption);

        return $options;
    }
}