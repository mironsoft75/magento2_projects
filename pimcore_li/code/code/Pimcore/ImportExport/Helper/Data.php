<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 5:59 PM
 * Filename: Data.php
 */

namespace Pimcore\ImportExport\Helper;


use Magento\Catalog\Model\Product\AttributeSet\Options;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollectionFactory;
use Magento\Eav\Api\AttributeManagementInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\File\Csv;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\Module\Dir\Reader;
use Magento\Framework\Oauth\OauthInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Pimcore\ImportExport\Model\Config;
use Psr\Log\LoggerInterface;

/**
 * Class Data
 * @package Pimcore\ImportExport\Helper
 */
class Data extends AbstractHelper
{
    const SIGNATURE_SHA1 = 'HMAC-SHA1';

    const SIGNATURE_SHA256 = 'HMAC-SHA256';
    const StatusNotFound = '404';
    /**
     * Specifies basic HTTP access authentication Header.
     *
     * @var string
     */
    private static $authorizationType = 'Authorization';

    /**
     * JSON HTTP Content-Type Header.
     *
     * @var string
     */
    private static $jsonDataType = 'application/json';

    private static $authorizationBearer = 'Bearer ';

    /**
     * @var string
     */
    private static $urlSeparator = '/';

    private static $authorization = "Authorization";

    /**
     * @var ZendClientFactory
     */
    private $httpClientFactory;
    /**
     * @var Config
     */
    private $modelConfig;
    /**
     * @var EncoderInterface
     */
    private $dataEncoder;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var OauthInterface
     */
    private $oauth;
    /**
     * @var UrlInterface
     */
    private $urlInterface;
    /**
     * @var Options
     */
    private $attributeSets;
    /**
     * @var CollectionFactory
     */
    private $categoryCollection;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    private $moduleReader;
    private $fileCsv;
    /**
     * @var AttributeRepositoryInterface
     */
    private $attributeRepository;
    /**
     * @var AttributeCollectionFactory
     */
    private $attributeCollection;
    /**
     * @var AttributeManagementInterface
     */
    private $attributeManagement;

    /**
     * Data constructor.
     * @param ZendClientFactory     $httpClientFactory
     * @param Config                $modelConfig
     * @param Json                  $dataEncoder
     * @param LoggerInterface       $logger
     * @param OauthInterface        $oauth
     * @param UrlInterface          $urlInterface
     * @param Options               $attributeSets
     * @param CollectionFactory     $categoryCollection
     * @param StoreManagerInterface $storeManager
     * @param Reader                $moduleReader
     * @param Csv                   $fileCsv
     */
    public function __construct(
        ZendClientFactory $httpClientFactory,
        Config $modelConfig,
        Json $dataEncoder,
        LoggerInterface $logger,
        OauthInterface $oauth,
        UrlInterface $urlInterface,
        Options $attributeSets,
        CollectionFactory $categoryCollection,
        StoreManagerInterface $storeManager,
        Reader $moduleReader,
        Csv $fileCsv,
        AttributeCollectionFactory $attributeCollection,
        AttributeManagementInterface $attributeManagement,
        ScopeConfigInterface $scopeConfig

    )
    {
        $this->httpClientFactory = $httpClientFactory;
        $this->modelConfig = $modelConfig;
        $this->dataEncoder = $dataEncoder;
        $this->logger = $logger;
        $this->oauth = $oauth;
        $this->urlInterface = $urlInterface;
        $this->attributeSets = $attributeSets;
        $this->categoryCollection = $categoryCollection;
        $this->storeManager = $storeManager;
        $this->moduleReader = $moduleReader;
        $this->fileCsv = $fileCsv;
        $this->attributeCollection = $attributeCollection;
        $this->attributeManagement = $attributeManagement;
        $this->scopeConfig = $scopeConfig;
    }

    public function getRestUrl($url)
    {
        $url = $this->urlInterface->getRouteUrl('rest/') . $url;
        return $url;
    }

    public function processResponse($request)
    {
        if ($request->getStatus() == self::StatusNotFound) {
            $response = ['message' => $request->getMessage(), 'status' => $request->getStatus()];
            return $this->dataEncoder->serialize($response);
        }
        return $request->getBody();
    }

    /**
     * @param $url
     * @return array|mixed
     */
    public function getApiData($url)
    {
        set_time_limit(0);
        try {
            $response = $this->getServiceResponse($url);
        } finally {
            ini_restore('max_execution_time');
        }
        return $response;
    }

    /**
     *
     */
    public function getRawUrlResposne($url, $timeout = 5)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $httpClient */
        $httpClient = $this->httpClientFactory->create();
        $result = [];
        try {
            $response = $httpClient->setUri(
                $url
            )->setConfig(
                [
                    'timeout' => $timeout,
                ]
            )->request(
                'GET'
            )->getBody();
            $result = $response;
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return $result;
    }

    /**
     * @param     $url
     * @param int $retry
     * @param int $timeout
     * @return array|mixed
     */
    private function getServiceResponse($url, $retry = 0, $timeout = 5)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $httpClient */
        $httpClient = $this->httpClientFactory->create();
        $response = [];
        try {
            $jsonResponse = $httpClient->setUri(
                $url
            )->setConfig(
                [
                    'timeout' => $timeout,
                ]
            )->request(
                'GET'
            )->getBody();

            $response = json_decode($jsonResponse, true);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return $response;
    }

    /**
     * @param       $url
     * @param       $method
     * @param array $params
     * @return array|bool|float|int|mixed|null|string
     */
    public function makeBasicAuthApiCall($url, $method, array $params = [])
    {
        $apiKey = $this->modelConfig->getApiKey();
        $client = $this->createBasicAuthClient($url, $apiKey, $method, $params);
        $result = null;
        try {
            $response = $this->processResponse($client->request());
            $result = $this->dataEncoder->unserialize($response);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
        return $result;
    }

    /**
     * @param       $url
     * @param       $method
     * @param array $params
     * @return \Magento\Framework\HTTP\ZendClient
     * @throws \Zend_Http_Client_Exception
     */
    private function createBasicAuthClient($apiUrl, $apiKey, $method, array $params = [])
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $client->setHeaders(
            self::$authorizationType,
            sprintf('Basic %s', base64_encode($apiKey))
        );
        $client->setHeaders(
            'Content-Type',
            self::$jsonDataType
        );
        if (!empty($params)) {
            $encodedData = $this->dataEncoder->serialize($params);
            $client->setRawData($encodedData, self::$jsonDataType);
        }
        $client->setMethod($method);
        $client->setUri($apiUrl);

        return $client;
    }

    /**
     * @param        $url
     * @param        $method
     * @param array  $rawData
     * @param string $token
     * @return array|bool|float|int|mixed|null|string
     */
    public function makeBearerApiCall(
        $urlPath,
        $method,
        array $rawData = [],
        $token = "6yd1xjb8g959b8nalvjtbb8iv52m9lvd"
    )
    {
        $result = '';
        $url = $this->getRestUrl($urlPath);
        $authorizationHeader = self::$authorizationBearer . $token;
        try {
            /** @var \Magento\Framework\HTTP\ZendClient $client */
            $client = $this->httpClientFactory->create();
            $client->setHeaders(
                self::$authorization,
                $authorizationHeader
            );
            $client->setHeaders(
                'Content-Type',
                self::$jsonDataType
            );
            if (!empty($rawData)) {
                $encodedData = $this->dataEncoder->serialize($rawData);
                $client->setRawData($encodedData, self::$jsonDataType);
            }
            $client->setMethod($method);
            $client->setUri($url);
            $response = $this->processResponse($client->request());
            $result = $this->dataEncoder->unserialize($response);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result = $e->getMessage();
        }
        return $result;
    }

    /**
     * @param $url
     * @param $method
     * @return mixed
     */
    public function makeOAuthApiCall(
        $urlPath,
        $method,
        array $rawData = [],
        $consumerKey = 'fhmevk79cmj7vio3lo8blwbtcs3ggoj0',
        $consumerSecret = '89e3w2du6c78ydi8imjqfrxpj8akh94b',
        $accessToken = 'er0vk2uaikkw54c9gqqonjjfby39wa46',
        $accessTokenSecret = 'bx0ntka08tkj9s8diqejhc7rbhx50upl'
    )
    {
        $result = '';
        $url = $this->getRestUrl($urlPath);
        $params = [
            "oauth_consumer_key" => $consumerKey,
            "oauth_consumer_secret" => $consumerSecret,
            "oauth_token" => $accessToken,
            "oauth_token_secret" => $accessTokenSecret,
            "oauth_signature_method" => self::SIGNATURE_SHA1,
            'oauth_nonce' => md5(uniqid(rand(), true)),
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0',
        ];


        try {
            $authorizationHeader = $this->oauth->buildAuthorizationHeader($params, $url, self::SIGNATURE_SHA1, $method);
            /** @var \Magento\Framework\HTTP\ZendClient $client */
            $client = $this->httpClientFactory->create();
            $client->setHeaders(
                self::$authorization,
                $authorizationHeader
            );
            $client->setHeaders(
                'Content-Type',
                self::$jsonDataType
            );
            if (!empty($rawData)) {
                $encodedData = $this->dataEncoder->serialize($rawData);
                $client->setRawData($encodedData, self::$jsonDataType);
            }
            $client->setMethod($method);
            $client->setUri($url);
            $response = $this->processResponse($client->request());
            $result = $this->dataEncoder->unserialize($response);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result = $e->getMessage();
        }
        return $result;

    }

    /**
     * @param string $type
     * @param        $username
     * @param        $password
     * @param string $method
     * @return array|bool|float|int|mixed|null|string
     */
    public function getBearerToken($username, $password, $type = 'admin', $method = "POST")
    {
        $result = '';
        $rawData = ['username' => $username, 'password' => $password];
        $url = $this->urlInterface->getRouteUrl("rest/V1/integration/$type/token");
        try {
            /** @var \Magento\Framework\HTTP\ZendClient $client */
            $client = $this->httpClientFactory->create();
            $client->setHeaders(
                'Content-Type',
                self::$jsonDataType
            );
            if (!empty($rawData)) {
                $encodedData = $this->dataEncoder->serialize($rawData);
                $client->setRawData($encodedData, self::$jsonDataType);
            }
            $client->setMethod($method);
            $client->setUri($url);
            $response = $this->processResponse($client->request());
            $result = $this->dataEncoder->unserialize($response);
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $result = $e->getMessage();
        }
        return $result;
    }

    public function getCategoryList()
    {
        $categories = $this->categoryCollection->create();
        $categories->addAttributeToSelect(['name', 'default_name']);
        $categoriesData = [];
        if ($categories) {
            foreach ($categories as $category) {
                $categoriesData[] = [$category->getName(), $category->getDefaultName(), $category->getId()];
            }
        }

        return $categoriesData;
    }

    public function getWebsitesList()
    {

        $websites = $this->storeManager->getWebsites();
        $websiteData = [];
        if (count($websites)) {
            foreach ($websites as $website) {
                $unsecureBaseUrl = $this->getConfigValue('web/unsecure/base_url', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE, $website->getId());
                $secureBaseUrl = $this->getConfigValue('web/secure/base_url', \Magento\Store\Model\ScopeInterface::SCOPE_WEBSITE, $website->getId());
                $websiteData[] = [$website->getName(), $unsecureBaseUrl, $secureBaseUrl, $website->getId()];
            }
        }

        return $websiteData;
    }

    public function getConfigValue($path, $scope, $scopeId)
    {
        return $this->scopeConfig->getValue($path, $scope, $scopeId);
    }

    public function getAttributeSetsList()
    {
        $attributeSets = $this->attributeSets->toOptionArray();
        $attributeSetsData = [];
        if (count($attributeSets)) {
            foreach ($attributeSets as $attributeSet) {
                $attributeSetsData[$attributeSet['label']] = $attributeSet['value'];
            }
        }

        return $attributeSetsData;
    }

    public function getAttributeManagement()
    {
        return $this->attributeManagement;
    }

    public function getAttributesList()
    {
        return $this->attributeCollection->create()->addFieldToFilter('is_user_defined', true);
    }

    public function readCsvFile($fileName)
    {
        $directory = $this->moduleReader->getModuleDir('', 'Pimcore_ImportExport');
        $file = $directory . '/Files/' . $fileName;
        $data = '';
        if (file_exists($file)) {
            $data = $this->fileCsv->getData($file);
            /*for($i=1; $i<count($data); $i++) {
                var_dump($data[$i]);
            }*/
        }
        return $data;
    }


}