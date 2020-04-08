<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 21/11/18
 * Time: 6:12 PM
 */

namespace Codilar\Videostore\Controller\Cart;

use Magento\Framework\App\Action\Action;
use Magento\Framework\Exception\LocalizedException;
use Codilar\Videostore\Helper\Email as HelperEmail;
use Codilar\Videostore\Helper\Otp as smsHelper;

class Request extends Action
{
    /**
     * @var
     */
    private $helperEmail;
    /**
     * @var
     */
    private $smsHelper;
    /**
     * @var \Codilar\Videostore\Model\VideostoreCartRepository
     */
    private $videostoreCartRepository;
    /**
     * @var \Codilar\Videostore\Model\VideostoreRequestRepository
     */
    private $videostoreRequestRepository;
    /**
     * @var \Codilar\Videostore\Model\VideostoreRequest
     */
    private $videostoreRequest;
    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $pageFactory;
    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    private $session;
    /**
     * @var \Magento\Customer\Model\Session
     */
    private $customerSession;

    /**
     * Request constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $pageFactory
     * @param \Codilar\Videostore\Model\VideostoreCartRepository $videostoreCartRepository
     * @param \Codilar\Videostore\Model\VideostoreRequestRepository $videostoreRequestRepository
     * @param \Codilar\Videostore\Model\VideostoreRequest $videostoreRequest
     * @param \Magento\Framework\Session\SessionManagerInterface $session
     * @param \Magento\Customer\Model\Session $customerSession
     * @param HelperEmail $helperEmail
     * @param smsHelper $smsHelper
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        \Codilar\Videostore\Model\VideostoreCartRepository $videostoreCartRepository,
        \Codilar\Videostore\Model\VideostoreRequestRepository $videostoreRequestRepository,
        \Codilar\Videostore\Model\VideostoreRequest $videostoreRequest,
        \Magento\Framework\Session\SessionManagerInterface $session,
        \Magento\Customer\Model\Session $customerSession,
        HelperEmail $helperEmail,
        smsHelper $smsHelper
    )
    {
        $this->helperEmail = $helperEmail;
        $this->smsHelper = $smsHelper;
        $this->videostoreCartRepository = $videostoreCartRepository;
        $this->videostoreRequestRepository = $videostoreRequestRepository;
        $this->videostoreRequest = $videostoreRequest;
        $this->pageFactory = $pageFactory;
        $this->session = $session;
        $this->customerSession = $customerSession;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws LocalizedException
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $videoRequest = $this->videostoreRequestRepository->create();
        if (!$this->smsHelper->formValidation($this->getRequest())) {
            $this->messageManager->addErrorMessage("Invalid Formkey");
            return $resultRedirect->setPath($this->_url->getUrl('video-store'));
        }
        $formData = $this->getRequest()->getParams();
        $state = (array_key_exists('state',$formData)) ? $formData['state'] : "NA";

        //otp verification is removed
//        if($this->smsHelper->verifyOtp($formData['otp'], $formData['mobile'])){
            if($this->customerSession->isLoggedIn()){
                $productCollection = $this->videostoreCartRepository->getCollection()
                    ->addFieldToFilter('videostore_customer_id',$this->customerSession->getCustomerId())
                    ->getColumnValues('product_id');
            }
            else{
                $productCollection = $this->videostoreCartRepository->getCollection()
                    ->addFieldToFilter('videostore_customer_session_id',$this->session->getSessionId())
                    ->getColumnValues('product_id');
           }
            $productIds = implode(",", $productCollection);
            $videoRequest->setData([
                'full_name' => $formData['fullname'],
                'email' => $formData['email'],
                'mobile_number' => $formData['mobile'],
                'requested_date' => $formData['tryonDate'],
                'videostore_product_ids' => $productIds,
                'requested_time' => $formData['tryonTime'],
                'country' => $formData['country'],
                'state' => $state,
                'message' => $formData['message'],
                'videostore_request_status' => 'Pending',
                'pending_flag' => 1,
            ]);

            if($this->customerSession->isLoggedIn()){
                $videoRequest->setData('videostore_customer_id',$this->customerSession->getCustomerId());
            }
            else{
                $videoRequest->setData('videostore_customer_id','');
            }
            /**
             * For sending SMS and Email
             */
            try{
                $this->videostoreRequestRepository->save($videoRequest);
                $this->smsHelper->sendRequestSubmitMessage($formData['mobile']);
                $this->helperEmail->sendEmailOnCustomerVerification($formData);

            }catch (\Exception $exception){
                throw new LocalizedException(__($exception->getMessage()));
            }
            /**
             * Deleting products from cart after submitting
             */
            try {
                $this->videostoreCartRepository->deleteProductsFromCart();
            } catch (\Exception $exception) {
                throw new LocalizedException(__("Error deleting products"));
            }
            $this->messageManager->addSuccessMessage("Your request has been submitted! Please check your Email for Details");
            return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
        }
//        else{
//            $this->messageManager->addErrorMessage("The OTP which you entered is Invalid");
//            return $resultRedirect->setUrl($this->_redirect->getRefererUrl());
//        }
   // }
}