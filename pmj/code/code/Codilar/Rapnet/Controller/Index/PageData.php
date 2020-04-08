<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/2/19
 * Time: 11:24 AM
 */

namespace Codilar\Rapnet\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class PageData
 * @package Codilar\Rapnet\Block
 */
class PageData extends \Magento\Framework\App\Action\Action
{

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;
    /**
     * @var Registry
     */
    private $_registry;


    /**
     * PageData constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Registry $registry
     * @param RapnetFactory $rapnetFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Registry $registry
    ) {
        $this->_registry = $registry;
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {
        /** @var  $resultPage \Magento\Framework\View\Result\Page */
        $resultPage = $this->_resultPageFactory->create();
        $block = $resultPage->getLayout()
            ->createBlock('Codilar\Rapnet\Block\PageData')
            ->setTemplate('pagedata.phtml')
            ->toHtml();
        $this->getResponse()->setBody($block);
    }
}
