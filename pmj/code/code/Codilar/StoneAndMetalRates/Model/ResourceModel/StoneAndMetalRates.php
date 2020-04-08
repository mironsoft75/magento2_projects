<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 7/11/18
 * Time: 8:56 PM
 */
namespace Codilar\StoneAndMetalRates\Model\ResourceModel;

/**
 * Class StoneAndMetalRates
 * @package Codilar\StoneAndMetalRates\Model\ResourceModel
 */
class StoneAndMetalRates extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
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
        $this->_init('stone_metal_rates', 'entity_id');
    }
}

