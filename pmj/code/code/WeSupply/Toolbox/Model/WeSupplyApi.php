<?php

namespace WeSupply\Toolbox\Model;

use WeSupply\Toolbox\Api\WeSupplyApiInterface;
use Magento\Framework\App\Response\Http;

class WeSupplyApi implements WeSupplyApiInterface
{
    const GRANT_TYPE = 'client_credentials';
    const TOKEN_PATH = 'oauth/token';
    const AUTH_ORDER_PATH = 'authLinks';
    const NOTIFICATION_PATH = 'trackers/phone/enrol';


    /**
     * @var
     */
    private $apiPath;

    private $apiClientId;

    private $apiClientSecret;


    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     *@var \Magento\Framework\HTTP\Client\Curl
     */
    protected $curlClient;



    public function __construct(
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->curlClient = $curl;
        $this->logger = $logger;
    }

    /**
     * @param $apiPath
     */
    public function setApiPath($apiPath)
    {
        $this->apiPath = $apiPath;
    }


    /**
     * @param $apiClientId
     */
    public function setApiClientId($apiClientId)
    {
        $this->apiClientId = $apiClientId;
    }


    /**
     * @param $apiClientSecret
     */
    public function setApiClientSecret($apiClientSecret)
    {
        $this->apiClientSecret = $apiClientSecret;
    }


    /**
     * @param $orderNo
     * @param $clientPhone
     * @return bool|mixed
     */
    public function notifyWeSupply($orderNo, $clientPhone)
    {
        $params = array("order" => $orderNo, "phone" => $clientPhone);
        $buildQuery = http_build_query($params);

        $this->curlClient->setOptions(
            array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_FOLLOWLOCATION => TRUE
            )
        );
        try {
            $url = 'https://'.$this->apiPath.self::NOTIFICATION_PATH.'?'.$buildQuery;
            $this->curlClient->get($url);
            $response = $this->curlClient->getBody();

            $jsonDecoded = json_decode($response, true);

            if($this->curlClient->getStatus() === Http::STATUS_CODE_403){
                return array('error' => 'Service not available');
            }
            elseif($this->curlClient->getStatus() === Http::STATUS_CODE_503){
                $this->logger->error('Error when sending SMS notif to Wesupply', $jsonDecoded ?? []);
                return $jsonDecoded;
            }elseif($this->curlClient->getStatus() === Http::STATUS_CODE_200){

                return true;
            }

            $this->logger->error('Error when sending SMS notif to Wesupply with status: '.$this->curlClient->getStatus(), $jsonDecoded ?? []);
            return  false;

        }catch(\Exception $e){
            $this->logger->error("WeSupply Notification Error:".$e->getMessage());
            return false;
        }
    }


    /**
     * @return bool|string
     */
    public function weSupplyAccountCredentialsCheck()
    {
        $accesToken = $this->getToken();
        return !empty($accesToken) ? true : false;
    }

    /**
     * @param $externalOrderIdString
     * @return bool|mixed
     */
    public function weSupplyInterogation($externalOrderIdString)
    {

        $accesToken = $this->getToken();

        if($accesToken)
        {
            $params = array("orders"=>$externalOrderIdString);

            $buildQuery = http_build_query($params);

            $this->curlClient->setOptions(
                array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_FOLLOWLOCATION => TRUE,
                CURLOPT_HTTPHEADER => array("Authorization: Bearer $accesToken")
                )
            );

            try {
                $url = 'https://'.$this->apiPath.self::AUTH_ORDER_PATH.'?'.$buildQuery;
                $this->curlClient->get($url);
                $response = $this->curlClient->getBody();
                $jsonDecoded = json_decode($response, true);

                return $jsonDecoded ?? false;
            }catch(\Exception $e){
                $this->logger->error("WeSupply API Error:".$e->getMessage());
                return false;
            }
         }

        return false;
    }


    private function getToken()
    {
        $authUrl = 'https://'.$this->apiPath.self::TOKEN_PATH;

        $userData = array(
            "grant_type"    => self::GRANT_TYPE,
            "client_id"     => $this->apiClientId,
            "client_secret" => $this->apiClientSecret
        );

        $this->curlClient->setHeaders(array('Content-Type: application/x-www-form-urlencoded'));

        try{
            $this->curlClient->post($authUrl, $userData);
            $response = $this->curlClient->getBody();
            $tokenDecoded = json_decode($response);
            return $tokenDecoded->access_token ?? '';
        }catch(\Exception $e){
            $this->logger->error("WeSupply API Error:".$e->getMessage());
            return '';
        }

    }
}