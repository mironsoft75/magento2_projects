<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 10/1/19
 * Time: 6:30 PM
 */

namespace Codilar\StoneAndMetalRates\Controller\AllJewellery;

/**
 * Class GetMetalRate
 * @package Codilar\StoneAndMetalRates\Controller\AllJewellery
 */
class GetMetalRate extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_pageFactory;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory)
    {
        $this->_pageFactory = $pageFactory;
        return parent::__construct($context);
    }

    /**
     * @return mixed
     */
    public function execute()
    {

        return $this->_pageFactory->create();
    }

}