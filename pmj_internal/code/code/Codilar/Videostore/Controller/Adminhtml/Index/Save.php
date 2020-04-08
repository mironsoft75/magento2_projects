<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 22/11/18
 * Time: 5:19 PM
 */

namespace Codilar\Videostore\Controller\Adminhtml\Index;

use Codilar\Videostore\Api\Data\VideostoreRequestActivityInterface;
use Codilar\Videostore\Api\VideostoreRequestActivityRepositoryInterface;
use Codilar\Videostore\Api\VideostoreRequestRepositoryInterface;
use Codilar\Videostore\Helper\Email as emailHelper;
use Codilar\Videostore\Helper\Otp as otpHelper;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Framework\View\Result\PageFactory;

class Save extends \Magento\Backend\App\Action
{
    protected $resultPageFactory = false;
    /**
     * @var VideostoreRequestRepositoryInterface
     */
    private $videostoreRequestRepository;

    /**
     * @var DateTime
     */
    protected $date;

    /**
     * @var TimezoneInterface
     */
    protected $timezone;
    /**
     * @var otpHelper
     */
    private $otpHelper;
    /**
     * @var emailHelper
     */
    private $emailHelper;
    /**
     * @var Session
     */
    private $authSession;
    /**
     * @var VideostoreRequestActivityInterface
     */
    private $videostoreRequestActivity;
    /**
     * @var VideostoreRequestActivityRepositoryInterface
     */
    private $videostoreRequestActivityRepository;

    /**
     * Save constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param VideostoreRequestRepositoryInterface $videostoreRequestRepository
     * @param DateTime $date ,
     * @param TimezoneInterface $timezone
     * @param emailHelper $emailHelper
     * @param otpHelper $otpHelper
     * @param Session $authSession
     * @param VideostoreRequestActivityInterface $videostoreRequestActivity
     * @param VideostoreRequestActivityRepositoryInterface $videostoreRequestActivityRepository
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        VideostoreRequestRepositoryInterface $videostoreRequestRepository,
        DateTime $date,
        TimezoneInterface $timezone,
        emailHelper $emailHelper,
        otpHelper $otpHelper,
        Session $authSession,
        VideostoreRequestActivityInterface $videostoreRequestActivity,
        VideostoreRequestActivityRepositoryInterface $videostoreRequestActivityRepository
    )
    {
        parent::__construct($context);
        $this->authSession = $authSession;
        $this->date = $date;
        $this->timezone = $timezone;
        $this->resultPageFactory = $resultPageFactory;
        $this->videostoreRequestRepository = $videostoreRequestRepository;
        $this->otpHelper = $otpHelper;
        $this->emailHelper = $emailHelper;
        $this->videostoreRequestActivity = $videostoreRequestActivity;
        $this->videostoreRequestActivityRepository = $videostoreRequestActivityRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     * @throws \Exception
     */
    public function execute()
    {
        $adminUser = $this->authSession->getUser()->getName();
        $adminEmail = $this->authSession->getUser()->getEmail();
        try {
            $formData = $this->getRequest()->getParams();

        $oldRequestStatus = $this->videostoreRequestRepository->getCollection()
            ->addFieldToFilter('videostore_request_id', $formData['request']['videostore_request_id'])
            ->getFirstItem()->getVideostoreRequestStatus();

        $oldRequestAssignee = $this->videostoreRequestRepository->getCollection()
            ->addFieldToFilter('videostore_request_id', $formData['request']['videostore_request_id'])
            ->getFirstItem()->getAssignedTo();

            $newRequestStatus = $formData['request']['videostore_request_status'];
            $newRequestAssignee = $formData['request']['assigned_to'];

            $request = $this->videostoreRequestRepository->load($formData['request']['videostore_request_id'], 'videostore_request_id');

            //current request product Ids
            $requestProductIds = $request->getVideostoreProductIds();

            switch ($newRequestStatus) {
                case 'Approved':
                    if (!$request->getAcceptFlag()) {
                        $this->emailHelper->sendMailAboutStatus($newRequestStatus, $formData['request']['email'], $formData['request']['full_name'], $requestProductIds);
                        $this->otpHelper->sendStatusMessage($formData['request']['mobile_number'],$newRequestStatus);
                        $request->setAcceptFlag(1);
                    }
                    break;
                case 'Rejected':
                    if (!$request->getRejectFlag()) {
                        $this->emailHelper->sendMailAboutStatus($newRequestStatus, $formData['request']['email'], $formData['request']['full_name'], $requestProductIds);
                        $this->otpHelper->sendStatusMessage($formData['request']['mobile_number'],$newRequestStatus);
                        $request->setRejectFlag(1);
                    }
                    break;
                case 'Processing':
                    if (!$request->getProcessingFlag()) {
                        $this->emailHelper->sendMailAboutStatus($newRequestStatus, $formData['request']['email'], $formData['request']['full_name'], $requestProductIds);
                        $this->otpHelper->sendStatusMessage($formData['request']['mobile_number'],$newRequestStatus);
                        $request->setProcessingFlag(1);
                    }
                    break;
                default:
                    $request->setVideostoreRequestStatus($newRequestStatus);
            }
            $request->setAssignedTo($newRequestAssignee);
            $request->setVideostoreRequestStatus($newRequestStatus);
            $request->setVideostoreRequestUpdatedAt($this->timezone->formatDateTime($this->date->gmtDate()));
            $this->videostoreRequestRepository->save($request);

            //saving request table activity
            if($oldRequestAssignee !== $newRequestAssignee || $oldRequestStatus !== $newRequestStatus ){
                $activityData = [
                    'admin' => $adminUser,
                    'email' => $adminEmail,
                    'oldStatus' => $oldRequestStatus ,
                    'newStatus' => $newRequestStatus,
                    'oldAssignee' => $oldRequestAssignee,
                    'newAssignee' => $newRequestAssignee,
                    'time' =>  $this->timezone->formatDateTime($this->date->date())
                ];
                $this->videostoreRequestActivity->setVideostoreRequestId($formData['request']['videostore_request_id']);
                $this->videostoreRequestActivity->setVideostoreRequestActivity(json_encode($activityData));
                $this->videostoreRequestActivityRepository->save($this->videostoreRequestActivity);
            }
        }
        catch (\Exception $exception){
            throw new LocalizedException(__($exception->getMessage()));
    }
        $resultRedirect = $this->resultRedirectFactory->create();
        return $resultRedirect->setPath('videostore/index/index');
    }

}