<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26/7/19
 * Time: 10:52 AM
 */

namespace Codilar\ViewCache\Helper;

use Codilar\ViewCache\Model\Config;
use Magento\Framework\App\Request\Http;
use Magento\Framework\HTTP\Client\CurlFactory;

class Data
{
    /**
     * @var Config
     */
    private $config;
    /**
     * @var CurlFactory
     */
    private $curlFactory;
    /**
     * @var Http
     */
    private $http;

    public function __construct(
        Config $config,
        CurlFactory $curlFactory,
        Http $http
    ) {
        $this->config = $config;
        $this->curlFactory = $curlFactory;
        $this->http = $http;
    }

    /**
     * @param string $urlKey
     * @return array
     */
    public function urlExecute($urlKey)
    {
        $url = $this->config->getCacheUrl();
        $key = $this->config->getKey();
        $curl = $this->curlFactory->create();
        $url =$url . "?" . 'key=' . $key . '&' . 'tag=' . $urlKey;
        $curl->get($url);
        $response = \json_decode($curl->getBody(), true);
        if ($response) {
            if (!array_key_exists('code', $response)) {
                $response['code'] = 500;
            }
            if (!array_key_exists('result', $response)) {
                $response['result'] = __("Error occurred");
            }
        } else {
            $response = [
                'code' => 404,
                'result' => __("Could not connect to Vue Cache URL")
            ];
        }
        return ['status' => $response['code'] == 200, 'message' => $response['result']];
    }
}
