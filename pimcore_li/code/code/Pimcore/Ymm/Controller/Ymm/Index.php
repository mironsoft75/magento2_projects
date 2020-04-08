<?php

namespace Pimcore\Ymm\Controller\Ymm;

use Magento\Customer\Model\Session;
use Pimcore\Ymm\Helper\Data;

class Index extends \Magento\Framework\App\Action\Action
{
    CONST YEAR_COL = 'year_id';
    CONST MAKE_COL = 'make_name';
    CONST MODEL_COL = 'model_name';
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Data
     */
    private $helper;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        Session $session,
        Data $helper
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->session = $session;
        $this->helper = $helper;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $clearSelectedYmm = $this->getRequest()->getParam('clearSelectedYmm');
        if (isset($clearSelectedYmm)) {
            $this->helper->clearSelectedYmm();
        }
    }
}
