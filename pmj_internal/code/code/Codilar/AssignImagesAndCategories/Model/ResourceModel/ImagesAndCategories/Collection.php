<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 3:26 PM
 */

namespace Codilar\AssignImagesAndCategories\Model\ResourceModel\ImagesAndCategories;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

/**
 * Class Collection
 *
 * @package Codilar\AssignImagesAndCategories\Model\ResourceModel\ImagesAndCategories
 */
class Collection extends AbstractCollection
{
    /**
     * EntityId
     *
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * EventPrefix
     *
     * @var string
     */
    protected $_eventPrefix = 'codilar_image_and_category';
    /**
     * EventObject
     *
     * @var string
     */
    protected $_eventObject = 'codilar_image_and_category';

    /**
     * Collection constructor.
     */
    public function _construct()
    {
        $this->_init('Codilar\AssignImagesAndCategories\Model\ImagesAndCategories',
            'Codilar\AssignImagesAndCategories\Model\ResourceModel\ImagesAndCategories');
    }
}