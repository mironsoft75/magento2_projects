<?php
/**
 * Created by PhpStorm.
 * User: mmjsm
 * Date: 30/8/17
 * Time: 12:59 PM
 */

namespace Codilar\Offers\Controller\Adminhtml\Cmsblock;


use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\View\Result\PageFactory;

class ProductTab extends \Magento\Framework\App\Action\Action
{

    protected $_resultPageFactory;
    protected $resultJsonFactory;
    protected $_errorHelper;
    protected $resultRawFactory;

    /**
     * @param Context           $context
     * @param PageFactory      $resultPageFactory
     * @param RawFactory $resultRawFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        RawFactory $resultRawFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;

        $this->resultRawFactory = $resultRawFactory;
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->_resultPageFactory->create();
        $block = $resultPage->getLayout()->getBlock('product_block');
        $this->getResponse()->appendBody($block->toHtml());
    }
}