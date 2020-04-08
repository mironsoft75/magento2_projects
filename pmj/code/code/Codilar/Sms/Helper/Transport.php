<?php

namespace Codilar\Sms\Helper;

use Codilar\Sms\Model\Config;
use Magento\Framework\Module\Dir;
use Psr\Log\LoggerInterface;
use Twilio\Rest\Client;
use Magento\Framework\Module\Dir\Reader;

class Transport{

    private $_config;
    private $_reader;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Transport constructor.
     * @param Config $config
     * @param Reader $reader
     * @param LoggerInterface $logger
     */
    public function __construct(
        Config $config,
        Reader $reader,
        LoggerInterface $logger
    )
    {
        $this->_config = $config;
        $this->_reader = $reader;
        $this->logger = $logger;
    }


    /**
     * @param $template
     * @param array $data
     * @return string
     */
    public function getSmsTemplate($template, $data = []){
        $template = explode("::", $template);
        if(count($template) < 2){
            $moduleName = "Codilar_Sms";
            $template = $template[0];
        }
        else{
            $moduleName = $template[0];
            $template = $template[1];
        }
        $templatePath = $this->_reader->getModuleDir(Dir::MODULE_VIEW_DIR, $moduleName).DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template;
        try {
            $template = \file_get_contents($templatePath);
            $template = preg_replace("/<\!--(.*?)-->/s", "", $template);
            $argumentCounter = 1;
            $arguments = [];
            foreach ($data as $key => $value) {
                $template = @str_replace("{{var $key}}", "%$argumentCounter", $template);
                $arguments[] = $value;
                $argumentCounter++;
            }
            $template = __($template, $arguments)->render();
        }
        catch(\Exception $e){
            $template = "";
        }
        return $template;
    }

    /**
     * @param string $number
     * @param string $data
     * @return array|bool
     */
    /*public function sendSms($number, $data){
        if(!$this->_config->isEnabled()){
            return false;
        }
        $authId = $this->_config->getAuthId();
        $authToken = $this->_config->getAuthToken();
        $souceNumber = $this->getStoreNumber();
        return $this->_sendSms($authId, $authToken, $souceNumber, $number, $data);
    }*/

    public function sendSms($number, $data){
        if(!$this->_config->isMsg91Enabled()){
            return false;
        }
        $authKey = $this->_config->getMsg91AuthKey();
        $senderId = $this->_config->getMsg91SenderId();
        return $this->_sendMsg91Sms($authKey, $senderId, $number, $data);
    }

    public function getStoreNumber(){
        return $this->_config->getSourceNumber();
    }

    /**
     * @param string $authId
     * @param string $authToken
     * @param string $source
     * @param string $destination
     * @param string $text
     * @return array
     */
    /*protected function _sendSms($authId, $authToken, $source, $destination, $text){
        try {
            $client = new Client($authId, $authToken);
            $response = $client->messages->create(
                $destination,
                [
                    'from' => $source,
                    'body' => $text
                ]
            );
            $response = $response->toArray();
            return ["message" => $response['status']];
        } catch (\Exception $exception) {
            $this->logger->info("Sms: ".$exception->getMessage());
        }
    }*/
    /**
     * @param $authKey
     * @param $senderId
     * @param $destination
     * @param $text
     * @return array
     */
    protected function _sendMsg91Sms($authKey, $senderId, $destination, $text){
        try {
            $mobileNumber = $destination;
            $mobileNumber = str_replace("+91","",$mobileNumber);
            $message = urlencode($text);
            //Define route
            $route = "4";
            //Prepare you post parameters
            $postData = array(
                'authkey' => $authKey,
                'mobiles' => $mobileNumber,
                'message' => $message,
                'sender' => $senderId,
                'route' => $route,
                'country' => 91
            );

            //API URL
            $url="http://api.msg91.com/api/sendhttp.php";

            // init the resource
            $ch = curl_init();
            curl_setopt_array($ch, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postData
                //,CURLOPT_FOLLOWLOCATION => true
            ));
            //Ignore SSL certificate verification
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


            //get response
            $output = curl_exec($ch);
            //Print error if any
            if(curl_errno($ch))
            {
                //echo 'error:' . curl_error($ch);
            }

            curl_close($ch);
            return true;
            /*$response = $response->toArray();
            return ["message" => $response['status']];*/
        } catch (\Exception $exception) {
            $this->logger->info("Error while sending Sms: ".$exception->getMessage());
            return false;
        }
    }


}