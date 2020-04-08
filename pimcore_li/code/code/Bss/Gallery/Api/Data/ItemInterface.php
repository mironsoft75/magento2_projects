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

interface ItemInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ITEM_ID = 'item_id';
    const CATEGORY_IDS = 'category_ids';
    const TITLE = 'title';
    const IMAGE = 'image';
    const VIDEO = 'video';
    const DESCRIPTION = 'description';
    const CREATE_TIME = 'create_time';
    const UPDATE_TIME = 'update_time';
    const IS_ACTIVE = 'is_active';
    const TYPE_ID = 'type_id';

    public function getId();

    public function getCategoryIds();

    public function getTitle();

    public function getThumbnail();

    public function getImage();

    public function getVideo();

    public function getType();

    public function getDescription();

    public function getCreateTime();

    public function getUpdateTime();

    public function isActive();

    public function setId($id);

    public function setCategoryIds($category_ids);

    public function setTitle($title);

    public function setThumbnail($thumbnail);

    public function setImage($image);

    public function setVideo($video);

    public function setType($type_id);

    public function setDescription($description);

    public function setCreateTime($createTime);

    public function setUpdateTime($updateTime);

    public function setIsActive($isActive);
}
