<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/4/19
 * Time: 6:35 PM
 */

namespace Codilar\ShippingPrice\Block\Order;


use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\ResourceModel\Order\Item\CollectionFactory as ItemCollectionFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Sales\Model\Order\Config;
use Magento\Sales\Api\OrderRepositoryInterface;

/**
 * Class History
 * @package Codilar\ShippingPrice\Block\Order
 */
class History extends \Magento\Sales\Block\Order\History
{
    /**
     * @var string
     */
    protected $_template = 'Codilar_ShippingPrice::order/history.phtml';
    /**
     * @return string
     */
    protected $orderRepository;
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\Order\Config
     */
    protected $_orderConfig;
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;
    /**
     * @var ItemCollectionFactory
     */
    protected $collectionFactory;

    /**
     * History constructor.
     * @param Context $context
     * @param CollectionFactory $orderCollectionFactory
     * @param Session $customerSession
     * @param Config $orderConfig
     * @param OrderRepositoryInterface $orderRepository
     * @param ItemCollectionFactory $collection
     * @param array $data
     */
    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory,
        Session $customerSession,
        Config $orderConfig,
        OrderRepositoryInterface $orderRepository,
        ItemCollectionFactory $collection,
        array $data = []
    )
    {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->_orderConfig = $orderConfig;
        $this->orderRepository = $orderRepository;
        $this->collectionFactory = $collection;
        parent::__construct($context, $orderCollectionFactory, $customerSession, $orderConfig, $data);
    }

    /**
     * @param $id
     * @return array
     */
    public function getDays($id)
    {
        /**
         * @var $days \Magento\Sales\Model\ResourceModel\Order\Item\Collection
         */
        $estimatedDay = $this->collectionFactory->create()->addFieldToFilter('order_id', $id);
        $createdAt = $estimatedDay->getData()[0] ["created_at"];
        $days = $estimatedDay->getData()[0] ["estimated_days"];
        $estimatedDay = array($createdAt, $days);
        return $estimatedDay;
    }

    /**
     * @param $id
     * @return false|string
     */
    public function getDate($id)
    {
        $dates=$this->getDays($id);
        if($dates[1]){
            $delivery =  ' + '.$dates[1].' days';
            return date('Y-m-d', strtotime($dates[0]. $delivery));
        }
    }
}
