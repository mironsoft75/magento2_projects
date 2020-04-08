<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30/11/18
 * Time: 12:42 PM
 */
namespace Codilar\CustomiseJewellery\Controller\Index;
/**
 * Class Index
 * @package Codilar\CustomiseJewellery\Controller\Index
 */
class Index extends \Magento\Framework\App\Action\Action
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
