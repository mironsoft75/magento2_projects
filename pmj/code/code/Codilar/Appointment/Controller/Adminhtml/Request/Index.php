<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/4/19
 * Time: 4:03 PM
 */

namespace Codilar\Appointment\Controller\Adminhtml\Request;

use Magento\Backend\App\Action;

/**
 * Class Index
 * @package Codilar\Appointment\Controller\Adminhtml\Request
 */
class Index extends Action
{
    const ADMIN_RESOURCE = "Codilar_Appointment::codilar_appointment_request";
    /**
     * @var bool|\Magento\Framework\View\Result\PageFactory
     */
    protected $resultPageFactory = false;

    /**
     * Index constructor.
     * @param Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->prepend((__('PMJ Appointment Request')));
        return $resultPage;
    }
}
