<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 9/4/19
 * Time: 10:49 AM
 */

namespace Codilar\Appointment\Controller\VideoStore;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Framework\Controller\Result\RedirectFactory;
use Codilar\Appointment\Model\AppointmentRequestFactory;
use Codilar\Appointment\Api\AppointmentRequestRepositoryInterface;
use Magento\Framework\HTTP\Client\Curl;
use Psr\Log\LoggerInterface;

/**
 * Class Result
 * @package Codilar\Appointment\Controller\VideoStore
 */
class Result extends \Magento\Framework\App\Action\Action
{
    CONST GROWTHEYE_URL = "https://growtheye.com/lead_api.php?";
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * @var Validator
     */
    private $validator;
    /**
     * @var
     */
    protected $redirectFactory;
    /**
     * @var AppointmentRequest
     */
    protected $_appointmentRequest;
    /**
     * @var AppointmentRequestInterface
     */
    protected $_appointmentRequestRepositoryInterface;
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var Curl
     */
    protected $_curl;


    /**
     * Result constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Validator $validator
     * @param AppointmentRequestFactory $appointmentRequest
     * @param RedirectFactory $redirectFactory
     * @param AppointmentRequestRepositoryInterface $appointmentRequestRepositoryInterface
     * @param Curl $curl
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Validator $validator,
        AppointmentRequestFactory $appointmentRequest,
        RedirectFactory $redirectFactory,
        AppointmentRequestRepositoryInterface $appointmentRequestRepositoryInterface,
        Curl $curl,
        LoggerInterface $logger


    )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->validator = $validator;
        $this->_appointmentRequest = $appointmentRequest;
        $this->_appointmentRequestRepositoryInterface = $appointmentRequestRepositoryInterface;
        $this->resultRedirectFactory = $redirectFactory;
        $this->_curl = $curl;
        $this->_logger = $logger;
        return parent::__construct($context);
    }

    /**
     * @param $params
     */
    private function _makeCurlRequest($params)
    {
        $url = self::GROWTHEYE_URL . http_build_query($params);
        $this->_curl->get($url);
        $this->_curl->getBody();
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->resultJsonFactory->create();
            /**
             * @var \Codilar\Appointment\Model\AppointmentRequest $appointment
             */
            $appointment = $this->_appointmentRequest->create();
            /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
            $resultRedirect = $this->resultRedirectFactory->create();
            if (!$this->validator->validate($this->getRequest())) {
                $this->messageManager->addErrorMessage('Invalid Formkey.');
                $resultRedirect->setPath('appointment/videostore/index');
                return $resultRedirect;
            }
            $date = $this->getRequest()->getParam('appointmentDate');
            $time = strtotime($date);
            $appointmentDate = date('d-m-Y', $time);
            $timeSlot = $this->getRequest()->getParam('timeSlot');
            $country = $this->getRequest()->getParam('country');
            $city = $this->getRequest()->getParam('city');
            $name = $this->getRequest()->getParam('name');
            $mobile = $this->getRequest()->getParam('mobile');
            $email = $this->getRequest()->getParam('email');
            $note = $this->getRequest()->getParam('note');
            $requestedUrl = $this->getRequest()->getParam('requestUrl');
            $videostoreRequestStatus = $this->getRequest()->getParam('videostoreRequestStatus');
            $appointmentRequestStatus = $this->getRequest()->getParam('appointmentRequestStatus');
            $sku = $this->getRequest()->getParam('sku');
            $appointment->setData([
                'requested_date' => $appointmentDate,
                'requested_time' => $timeSlot,
                'country' => $country,
                'place' => $city,
                'full_name' => $name,
                'mobile_number' => $mobile,
                'email' => $email,
                'note' => $note,
                'request_url' => $requestedUrl,
                'videostore_request_status' => $videostoreRequestStatus,
                'appointment_request_status' => $appointmentRequestStatus,
                'request_product_sku' => $sku,
            ]);
            $params = [
                "name" => $name,
                "email" => $email,
                "phone" => $mobile,
                "source" => "website",
                "country"=> $country,
                "additional_col1" => $city,
                "access_code" => "34BA-19FC-A2B9-E0A0-79D0",
            ];
            $this->_makeCurlRequest($params);
            $this->_appointmentRequestRepositoryInterface->save($appointment);
            $response['status'] = "OK";
            $response['msg'] = 'Your submission has been received';
            return $result->setData($response);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

}