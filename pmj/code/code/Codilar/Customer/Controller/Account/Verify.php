<?php

namespace Codilar\Customer\Controller\Account;

use Magento\Customer\Controller\AbstractAccount;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Class Verify
 * @package Codilar\Customer\Controller\Account
 */
class Verify extends AbstractAccount
{
    /**
     * @var PageFactory
     */
    protected $_pageFactory;

    /**
     * Verify constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    )
    {
        $this->_pageFactory = $pageFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $page = $this->_pageFactory->create();
        return $page;
    }
}