<?php
namespace Codilar\CustomerLogin\Block;
  
class Popup extends \Magento\Framework\View\Element\Template
{   
	protected $_session;
    public $httpContext;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
         \Magento\Framework\App\Http\Context $httpContext,
        array $data = []
    )
    {
    	$this->_objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    	$this->_customerSession = $this->_objectManager->get('Magento\Customer\Model\Session');
         $this->httpContext = $httpContext;
        parent::__construct($context, $data);
        // parent::__construct($context);
    }

    public function isLoggedIn()
    {
        // echo "nadeem pasha".$this->_customerSession->isLoggedIn();
    	return $this->_customerSession->isLoggedIn();
    }

     public function getLogin() {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $userContext = $objectManager->get('Magento\Framework\App\Http\Context');
        $isLoggedIn = $userContext->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
        return $isLoggedIn;
    }
}