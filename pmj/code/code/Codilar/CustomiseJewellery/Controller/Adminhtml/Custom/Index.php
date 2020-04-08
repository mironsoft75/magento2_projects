<?php
/**
 * Created by PhpStorm.
 * User: atul
 * Date: 30/11/18
 * Time: 10:58 AM
 */

namespace Codilar\CustomiseJewellery\Controller\Adminhtml\Custom;


use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Index
 * @package Codilar\CustomiseJewellery\Controller\Adminhtml\Custom
 */
class Index extends Action
{

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param Action\Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory
    )
    {
        parent::__construct($context);
        $this->_resultPageFactory = $resultPageFactory;
    }

    /**
     * Index action
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_resultPageFactory->create();
        $resultPage->addBreadcrumb(__('CustomiseJewellery'), __('CustomiseJewellery'));
        $resultPage->addBreadcrumb(__('CustomiseJewellery '), __('CustomiseJewellery '));
        $resultPage->getConfig()->getTitle()->prepend(__('Customise Jewellery'));;

        return $resultPage;
    }
}
