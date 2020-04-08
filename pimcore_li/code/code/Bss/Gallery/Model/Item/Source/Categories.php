<?php
/** 
 * BSS Commerce Co. 
 * 
 * NOTICE OF LICENSE 
 * 
 * This source file is subject to the EULA 
 * that is bundled with this package in the file LICENSE.txt. 
 * It is also available through the world-wide-web at this URL: 
 * http://bsscommerce.com/Bss-Commerce-License.txt 
 * 
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE 
 * ================================================================= 
 * This package designed for Magento COMMUNITY edition 
 * BSS Commerce does not guarantee correct work of this extension 
 * on any other Magento edition except Magento COMMUNITY edition. 
 * BSS Commerce does not provide extension support in case of 
 * incorrect edition usage. 
 * ================================================================= 
 * 
 * @category   BSS 
 * @package    Bss_Gallery 
 * @author     Extension Team 
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com ) 
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt 
 */ 
namespace Bss\Gallery\Model\Item\Source;

class Categories implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Bss\Gallery\Model\Item
     */
    protected $item;
    protected $_categories;
    protected $request;

    /**
     * Constructor
     *
     * @param \Bss\Gallery\Model\Item $item
     */
    public function __construct(\Bss\Gallery\Model\Item $item, \Bss\Gallery\Model\ResourceModel\Category\CollectionFactory $categories, \Magento\Framework\App\Request\Http $request)
    {
        $this->item = $item;
        $this->request = $request;
        $this->_categories = $categories;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = array();
        $allcategories = $this->_categories->create();
        foreach ($allcategories as $cate) {
            $options[] = [
                'label' => $cate->getTitle(),
                'value' => $cate->getId(),
            ];
        }
        return $options;
    }

    public function getCategoryIds()
    {
        if ($this->request->getParam('item_id')) {
            $categoryIds = $this->item->load($this->request->getParam('item_id'))->getCategoryIds();
            return explode(',', $categoryIds);
        } else {
            return array();
        }
    }
}
