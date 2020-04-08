<?php

namespace Pimcore\Ymm\Controller\Ymm;

use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;

class AttributeOptions extends \Magento\Framework\App\Action\Action
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
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context      $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        UrlInterface $url,
        Session $session
    )
    {
        $this->resultPageFactory = $resultPageFactory;
        parent::__construct($context);
        $this->url = $url;
        $this->session = $session;
    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        /*Set YMM  data in customer session - change it later to cookies*/
        $this->setInSession($params);
        $params = array_filter($params);
        $url = $this->url->getUrl('all-products', ['_query' => $params]);
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setUrl($url);
        return $resultRedirect;
    }

    private function setInSession($params)
    {
        $ymm = [self::YEAR_COL, self::MAKE_COL, self::MODEL_COL];
        foreach ($ymm as $value) {
            if(isset($params[$value])){
                $this->session->setData($value, $params[$value]);
            }

        }
    }

}
