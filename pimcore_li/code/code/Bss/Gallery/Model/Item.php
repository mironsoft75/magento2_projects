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
namespace Bss\Gallery\Model;

use Bss\Gallery\Api\Data\ItemInterface;
use Magento\Framework\DataObject\IdentityInterface;

class Item extends \Magento\Framework\Model\AbstractModel implements ItemInterface, IdentityInterface
{

    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    const CACHE_TAG = 'gallery_item';
    protected $_cacheTag = 'gallery_item';
    protected $_eventPrefix = 'gallery_item';

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = [])
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    protected function _construct()
    {
        $this->_init('Bss\Gallery\Model\ResourceModel\Item');
    }

    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => __('Enabled'), self::STATUS_DISABLED => __('Disabled')];
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getId()
    {
        return $this->getData(self::ITEM_ID);
    }

    public function getCategoryIds()
    {
        return $this->getData(self::CATEGORY_IDS);
    }

    public function getTitle()
    {
        return $this->getData(self::TITLE);
    }

    public function getThumbnail()
    {
        return $this->getData(self::THUMBNAIL);
    }

    public function getImage()
    {
        return $this->getData(self::IMAGE);
    }

    public function getVideo()
    {
        return $this->getData(self::VIDEO);
    }

    public function getType()
    {
        return $this->getData(self::TYPE_ID);
    }

    public function getDescription()
    {
        return $this->getData(self::DESCRIPTION);
    }

    public function getCreateTime()
    {
        return $this->getData(self::CREATE_TIME);
    }

    public function getUpdateTime()
    {
        return $this->getData(self::UPDATE_TIME);
    }

    public function isActive()
    {
        return (bool)$this->getData(self::IS_ACTIVE);
    }

    public function setId($id)
    {
        return $this->setData(self::ITEM_ID, $id);
    }

    public function setCategoryIds($category_ids)
    {
        return $this->setData(self::CATEGORY_IDS, $category_ids);
    }

    public function setTitle($title)
    {
        return $this->setData(self::TITLE, $title);
    }

    public function setThumbnail($thumbnail)
    {
        return $this->setData(self::THUMBNAIL, $thumbnail);
    }

    public function setImage($image)
    {
        return $this->setData(self::IMAGE, $image);
    }

    public function setVideo($video)
    {
        return $this->setData(self::VIDEO, $video);
    }

    public function setType($type_id)
    {
        return $this->setData(self::TYPE_ID, $type_id);
    }

    public function setDescription($description)
    {
        return $this->setData(self::DESCRIPTION, $description);
    }

    public function setCreateTime($create_time)
    {
        return $this->setData(self::CREATE_TIME, $create_time);
    }

    public function setUpdateTime($update_time)
    {
        return $this->setData(self::UPDATE_TIME, $update_time);
    }

    public function setIsActive($is_active)
    {
        return $this->setData(self::IS_ACTIVE, $is_active);
    }

}
