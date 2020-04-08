<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutPaypal\Helper;

use Codilar\CheckoutPaypal\Model\Config;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\Client\CurlFactory;
use function Pulsestorm\Magento2\Cli\Fix_Direct_Om\validateResults;

class PaypalApi
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var Curl
     */
    private $curl;
    /**
     * @var CurlFactory
     */
    private $curlFactory;

    /**
     * PaypalApi constructor.
     * @param Config $config
     * @param Curl $curl
     * @param CurlFactory $curlFactory
     */
    public function __construct(
        Config $config,
        Curl $curl,
        CurlFactory $curlFactory
    )
    {
        $this->config = $config;
        $this->curl = $curl;
        $this->curlFactory = $curlFactory;
    }

    /**
     * @return string
     */
    public function getPaypalAccessToken()
    {
        $curl = $this->getCurlObject();
        $clientId = $this->config->getClientId();
        $secret = $this->config->getSecret();
        $curl->addHeader("Content-Type",'application/json');
        $curl->setCredentials($clientId,$secret);
        $curl->post($this->getPaypalUrl()."/v1/oauth2/token","grant_type=client_credentials");
        $response = $curl->getBody();
        return json_decode($response);
    }

    /**
     * @param string $accessToken
     * @param $orderData
     * @return mixed|string
     */
    public function getPaypalOrderResponse($accessToken, $orderData)
    {
        $curl = $this->getCurlObject();
        $curl->addHeader("Content-Type",'application/json');
        $curl->addHeader("Authorization", 'Bearer '. $accessToken);
        $curl->post($this->getPaypalUrl()."/v1/payments/payment",json_encode($orderData));
        $response = $curl->getBody();
        return json_decode($response);
    }

    /**
     * @param string $payPalPaymentId
     * @param string$data
     * @return mixed
     */
    public function checkPaypalConformation($payPalPaymentId, $data)
    {
        $accessTokenResponse = $this->getPaypalAccessToken();
        $accessToken = $accessTokenResponse->access_token;
        $curl = $this->getCurlObject();
        $curl->addHeader("Content-Type",'application/json');
        $curl->addHeader("Authorization", 'Bearer ' . $accessToken);
        $curl->post($this->getPaypalUrl()."/v1/payments/payment/" . $payPalPaymentId . "/execute",json_encode($data));
        $response = $curl->getBody();
        return json_decode($response);
    }

    /**
     * @return Curl
     */
    private function getCurlObject()
    {
        return $this->curlFactory->create();
    }

    /**
     * @return string
     */
    private function getPaypalUrl()
    {
        return $this->config->getPaymentUrl();
    }
}