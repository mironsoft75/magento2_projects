<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 17/12/18
 * Time: 10:37 AM
 */

namespace Codilar\Base\Controller\Product;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

class DiamondDetails extends \Magento\Framework\App\Action\Action
{
    /**
     * @var Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var Registry
     */
    private $_registry;

    /**
     * DiamondDetails constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry

    ) {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
        $this->_registry = $registry;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        $productId = $this->getRequest()->getParam('product_id');
        $this->_registry->register('simple_configurable_product_id',$productId);
        $resultPage = $this->_resultPageFactory->create();
        $block = $resultPage->getLayout()
            ->createBlock('Codilar\Base\Block\Common')
            ->setTemplate('Magento_Catalog::product/view/diamond_details.phtml')
            ->toHtml();
        $this->getResponse()->setBody($block);
    }
}