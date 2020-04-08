<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/10/19
 * Time: 3:24 PM
 */

namespace Codilar\AssignImagesAndCategories\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class ImagesAndCategories
 *
 * @package Codilar\AssignImagesAndCategories\Model\ResourceModel
 */
class ImagesAndCategories extends AbstractDb
{
    /**
     * EntityId
     *
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * Date
     *
     * @var date
     */
    protected $_date;

    /**
     * ImagesAndCategories constructor.
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param null $resourcePrefix
     */
    public function __construct(\Magento\Framework\Model\ResourceModel\Db\Context $context,
                                $resourcePrefix = null) {
        parent::__construct($context, $resourcePrefix);
    }
    protected function _construct()
    {
        $this->_init('codilar_image_and_category', 'entity_id');
    }
}