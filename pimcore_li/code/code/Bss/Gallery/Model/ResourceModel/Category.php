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
namespace Bss\Gallery\Model\ResourceModel;

/**
 * Gallery Category mysql resource
 */
class Category extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    private $itemFactory;

    /**
     * Construct
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Bss\Gallery\Model\ItemFactory $itemFactory,
        $resourcePrefix = null
    )
    {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
        $this->itemFactory = $itemFactory;
    }

    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('bss_gallery_category', 'category_id');
    }

    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {

        // if (!$this->isValidCategoryUrlKey($object)) {
        //     throw new \Magento\Framework\Exception\LocalizedException(
        //         __('The gallery URL key contains capital letters or disallowed symbols.')
        //     );
        // }

        // if ($this->isNumericCategoryUrlKey($object)) {
        //     throw new \Magento\Framework\Exception\LocalizedException(
        //         __('The gallery URL key cannot be made of only numbers.')
        //     );
        // }

        $itemCollection = $this->itemFactory->create()->getCollection()->getItems();
        $itemIds = $object->getData('Item_ids');
        $itemList = explode(',', $itemIds);
        foreach ($itemList as $itemId) {
            if (!array_key_exists($itemId, $itemCollection)) {
                unset($itemList[array_search($itemId, $itemList)]);
            }
        }
        $object->setData('Item_ids', implode(',', $itemList));

        if ($object->isObjectNew() && !$object->hasCreateTime()) {
            $object->setCreateTime($this->_date->gmtDate());
        }

        $object->setUpdateTime($this->_date->gmtDate());

        return parent::_beforeSave($object);
    }
    
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $itemCollection = $this->itemFactory->create();
        foreach (explode(',', $object->getData('Item_ids')) as $itemId) {
            $item = $itemCollection->load($itemId);
            $itemCateList = explode(',', $item->getCategoryIds());
            if (array_search($object->getCategoryId(), $itemCateList) !== false) {
                unset($itemCateList[array_search($object->getCategoryId(), $itemCateList)]);
                $item->setCategoryIds(implode(',', $itemCateList));
                $item->save();
            }
        }
        return parent::_beforeDelete($object);
    }

    /**
     * Load an object using 'category_id' field if there's no field specified and value is not numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @param mixed $value
     * @param string $field
     * @return $this
     */
    public function load(\Magento\Framework\Model\AbstractModel $object, $value, $field = null)
    {
        if (!is_numeric($value) && is_null($field)) {
            $field = 'url_key';
        }

        return parent::load($object, $value, $field);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param \Bss\Gallery\Model\Category $object
     * @return \Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);

        if ($object->getStoreId()) {

            $select->where(
                'is_active = ?',
                1
            )->limit(
                1
            );
        }

        return $select;
    }

    /**
     * Retrieve load select with filter by category_id and activity
     *
     * @param string $category_id
     * @param int $isActive
     * @return \Magento\Framework\DB\Select
     */
    protected function _getLoadByUrlKeySelect($url_key, $isActive = null)
    {
        $select = $this->getConnection()->select()->from(
            ['bp' => $this->getMainTable()]
        )->where(
            'bp.url_key = ?',
            $url_key
        );

        if (!is_null($isActive)) {
            $select->where('bp.is_active = ?', $isActive);
        }
        return $select;
    }

    /**
     *  Check whether category url key is numeric
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isNumericCategoryUrlKey(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[0-9]+$/', $object->getData('url_key'));
    }

    /**
     *  Check whether post url key is valid
     *
     * @param \Magento\Framework\Model\AbstractModel $object
     * @return bool
     */
    protected function isValidCategoryUrlKey(\Magento\Framework\Model\AbstractModel $object)
    {
        return preg_match('/^[a-z0-9][a-z0-9_\/-]+(\.[a-z0-9_-]+)?$/', $object->getData('url_key'));
    }

    /**
     * Check if category url key exists
     * return category id if url exists
     *
     * @param string $url_key
     * @return int
     */
    public function checkUrlKey($url_key)
    {
        $select = $this->_getLoadByUrlKeySelect($url_key, 1);
        $select->reset(\Zend_Db_Select::COLUMNS)->columns('bp.category_id')->limit(1);

        return $this->getConnection()->fetchOne($select);
    }
}
