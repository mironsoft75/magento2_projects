<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 3/12/18
 * Time: 4:16 PM
 */

namespace Codilar\Videostore\Controller\Otp;
use Codilar\Videostore\Helper\Otp as OtpHelper;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class SendOtp extends Action
{
    protected $_pageFactory;
    /**
     * @var OtpHelper
     */
    private $otpHelper;

    /**
     * Index constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param PageFactory $pageFactory
     * @param OtpHelper $otpHelper
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        OtpHelper $otpHelper
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->otpHelper = $otpHelper;
        parent::__construct($context);
    }

    public function execute()
    {
        $mobile = $this->_request->getParam('mobile_number');
        $this->otpHelper->sendOtp($mobile);
    }
}