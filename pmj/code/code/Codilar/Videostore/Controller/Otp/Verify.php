<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 3/12/18
 * Time: 4:14 PM
 */

namespace Codilar\Videostore\Controller\Otp;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Codilar\Videostore\Helper\Otp as otpHelper;
use Magento\Framework\Controller\ResultFactory;

class Verify extends Action
{
    /**
     * @var otpHelper
     */
    private $otpHelper;

    /**
     * Verify constructor.
     * @param Context $context
     * @param otpHelper $otpHelper
     */
    public function __construct(
        Context $context,
        otpHelper $otpHelper

    )
    {
        parent::__construct($context);
        $this->otpHelper = $otpHelper;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $otp = $this->_request->getParam('otp');
        $mobile = $this->_request->getParam('mobile_number');
        if($this->otpHelper->verifyOtp($otp, $mobile)){
            $response['status'] = true;
        }
        else{
            $response['status'] = false;
        }
        $result = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $result->setData($response);
        return $result;
    }
}