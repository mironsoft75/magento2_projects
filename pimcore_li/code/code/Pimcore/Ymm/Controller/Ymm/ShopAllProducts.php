<?php

namespace Pimcore\Ymm\Controller\Ymm;

use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\UrlInterface;
use Pimcore\Ymm\Helper\Data;

class ShopAllProducts extends \Magento\Framework\App\Action\Action
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
     * @var JsonFactory
     */
    private $resultJsonFactory;


    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        UrlInterface $url,
        Session $session,
        Data $helper,
        JsonFactory $resultJsonFactory

    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->url = $url;
        $this->session = $session;
        $this->helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $helper = $this->helper;
        $params = [
            'isYmmSelected' => $helper->isYmmSelected(),
            'allProductsUrl' => $helper->getAllProductsUrl()
        ];
        $result = $this->resultJsonFactory->create();
        $result->setData($params);
        return $result;
    }
}
