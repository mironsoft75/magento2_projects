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
namespace Bss\Gallery\Api\Data;

interface CategoryInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const CATEGORY_ID = 'category_id';
    const TITLE = 'title';
    const URL_KEY = 'url_key';
    const THUMBNAIL = 'thumbnail';
    const CREATE_TIME = 'create_time';
    const UPDATE_TIME = 'update_time';
    const IS_ACTIVE = 'is_active';
    const ITEM_IDS = 'item_ids';

    public function getId();

    public function getTitle();

    public function getUrlKey();

    public function getThumbnail();

    public function getCreateTime();

    public function getUpdateTime();

    public function isActive();

    public function getItemIds();

    public function setId($id);

    public function setUrlKey($url_key);

    public function setTitle($title);

    public function setThumbnail($thumbnail);

    public function setCreateTime($createTime);

    public function setUpdateTime($updateTime);

    public function setIsActive($isActive);

    public function setItemIds($item_ids);
}
