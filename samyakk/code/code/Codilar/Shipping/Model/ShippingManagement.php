<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Model;


use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Shipping\Api\ShippingManagementInterface;
use Codilar\Shipping\Api\Data\NotifyResponseInterface;
use Codilar\Shipping\Api\Data\NotifyResponseInterfaceFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Webapi\Rest\Response;

class ShippingManagement extends AbstractApi implements ShippingManagementInterface
{
    const ZIPCODE_VALIDATION = "/^[1-9][0-9]{5}$/";
    /**
     * @var Config
     */
    private $config;
    /**
     * @var NotifyResponseInterfaceFactory
     */
    private $notifyResponseFactory;

    /**
     * ShippingManagement constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param Config $config
     * @param NotifyResponseInterfaceFactory $notifyResponseFactory
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        Config $config,
        NotifyResponseInterfaceFactory $notifyResponseFactory
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->config = $config;
        $this->notifyResponseFactory = $notifyResponseFactory;
    }

    /**
     * @param string $zipcode
     * @return \Codilar\Shipping\Api\Data\NotifyResponseInterface
     */
    public function checkDelivery($zipcode)
    {
        $allowedZipcodes = array_column($this->config->getAllowedZipcodes(), 'zipcode');
        /** @var NotifyResponseInterface $response */
        $response = $this->notifyResponseFactory->create();
        if (preg_match(self::ZIPCODE_VALIDATION, $zipcode)){
            if (in_array($zipcode, $allowedZipcodes)) {
                $response->setStatus(true)->setMessage(__("Cash On Delivery available at %1", $zipcode));
            } else {
                $response->setStatus(false)->setMessage(__("Cash On Delivery unavailable at %1", $zipcode));
            }
        } else {
            $response->setStatus(false)->setMessage(__("Invalid Pincode")); 
        }
        return $this->sendResponse($response);
    }
}