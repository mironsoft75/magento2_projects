<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 30/11/18
 * Time: 10:48 AM
 */
namespace Codilar\CustomiseJewellery\Model\ResourceModel;

/**
 * Class CustomiseJewellery
 * @package Codilar\CustomiseJewellery\Model\ResourceModel
 */
class CustomiseJewellery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * @var string
     */
    protected $_idFieldName = 'entity_id';
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * Construct.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param string|null $resourcePrefix
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    )
    {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
    }

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init('codilar_customise_jewellery', 'entity_id');
    }
}