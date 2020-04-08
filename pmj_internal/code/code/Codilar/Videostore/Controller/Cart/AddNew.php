<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 21/11/18
 * Time: 10:47 PM
 */

namespace Codilar\Videostore\Controller\Cart;

use Codilar\Videostore\Api\Data\VideostoreCartInterface;
use Codilar\Videostore\Api\VideostoreCartRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;

class AddNew extends Action
{
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $pageFactory;
    /**
     * @var \Codilar\Videostore\Model\VideostoreCart
     */
    private $videostoreCart;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private  $customerSession;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    private $session;
    /**
     * @var \Codilar\Videostore\Api\VideostoreCartRepositoryInterface
     */
    private $videostoreCartRepository;
    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * AddNew constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     * @param VideostoreCartInterface $videostoreCart
     * @param VideostoreCartRepositoryInterface $videostoreCartRepository
     * @param SessionManagerInterface $session
     * @param Session $customerSession,
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        VideostoreCartInterface $videostoreCart,
        VideostoreCartRepositoryInterface $videostoreCartRepository,
        SessionManagerInterface $session,
        Session $customerSession,
        JsonFactory $resultJsonFactory
    )
    {
        $this->videostoreCart = $videostoreCart;
        $this->videostoreCartRepository = $videostoreCartRepository;
        $this->pageFactory = $pageFactory;
        $this->session = $session;
        $this->customerSession = $customerSession;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $cart = $this->videostoreCart;
        $resultRedirect = $this->resultRedirectFactory->create();
        $result = $this->resultJsonFactory->create();
        try{
            if($this->getRequest()->getParam('product-id')){
                $productId = $this->getRequest()->getParam('product-id');
                if($this->customerSession->isLoggedIn()){
                    if(count($this->videostoreCartRepository->getCollection()
                        ->addFieldToFilter('videostore_customer_id', $this->customerSession->getCustomerId())
                        ->addFieldToFilter('product_id',$productId ))){
                        $this->messageManager->addWarningMessage('Product already in cart');
                        $result->setData(['success' => false,'value'=>'Product already in cart']);
                    }
                    else{
                        $cart->setProductID($productId);
                        $cart->setVideostoreCustomerId($this->customerSession->getCustomerId());
                        $this->messageManager->addSuccessMessage('Product added successfully');
                        $result->setData(['success' => true,'value'=>'Product added successfully']);
                    }
                }
                else{
                    if(count($this->videostoreCartRepository->getCollection()
                        ->addFieldToFilter('videostore_customer_session_id', $this->session->getSessionId())
                        ->addFieldToFilter('product_id',$productId ))){
                        $this->messageManager->addWarningMessage('Product already in cart');
                        $result->setData(['success' => false,'value'=>'Product already in cart']);
                    }
                    else{
                        $cart->setProductID($productId);
                        $cart->setVideostoreCustomerSessionId($this->session->getSessionId());
                        $this->messageManager->addSuccessMessage('Product added successfully');
                        $result->setData(['success' => true,'value'=>'Product added successfully']);
                    }
                }
                $this->videostoreCartRepository->save($cart);
                return $result;
            }
            else{
                return $resultRedirect->setRefererOrBaseUrl();
            }
        }
        catch (\Exception $exception){
            throw new LocalizedException(__("Problem adding product to cart ". $exception->getMessage()));
        }
    }
}