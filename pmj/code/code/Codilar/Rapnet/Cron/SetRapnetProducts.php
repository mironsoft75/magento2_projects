<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 22/2/19
 * Time: 9:31 AM
 */

namespace Codilar\Rapnet\Cron;

use Magento\Framework\App\Helper\Context as Helper;
use Codilar\Rapnet\Model\Storage\DbStorage;
use Psr\Log\LoggerInterface;
use Codilar\Rapnet\Helper\Data;
use Codilar\Rapnet\Api\RapnetRepositoryInterface;

/**
 * Class SetRapnetProducts
 * @package Codilar\Rapnet\Cron
 */
class SetRapnetProducts
{
    const DIAMOND_PRODUCT = "Diamond Product";
    const XML_PATH_RAPNET_ENABLED = 'rapnet_section/settings/rapnet';
    const XML_PATH_USERNAME = 'rapnet_section/settings/username';
    const XML_PATH_PASSWORD = 'rapnet_section/settings/password';
    const XML_PATH_SLIDER_COLOR = 'rapnet_section/settings/slider_colour';
    const XML_PATH_HOVER_COLOR = 'rapnet_section/settings/hover_colour';

    /**
     * @var RapnetRepositoryInterface
     */
    protected $rapnetRepository;
    /**
     * @var Helper
     */
    protected $_helper;
    /**
     * @var DbStorageFactory
     */
    protected $_dbStorageFactory;
    /**
     * @var LoggerInterface
     */
    private $_logger;
    /**
     * @var Data
     */
    protected $_helperData;

    /**
     * SetRapnetProducts constructor.
     *
     * @param Helper $helper
     * @param DbStorage $dbStorage
     * @param LoggerInterface $logger
     * @param Data $data
     * @param RapnetRepositoryInterface $rapnetRepository
     */
    public function __construct(
        Helper $helper,
        DbStorage $dbStorage,
        LoggerInterface $logger,
        Data $data,
        RapnetRepositoryInterface $rapnetRepository
    )
    {
        $this->_helper = $helper;
        $this->_dbStorageFactory = $dbStorage;
        $this->_logger = $logger;
        $this->_helperData = $data;
        $this->rapnetRepository = $rapnetRepository;
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->_helper->getScopeConfig()->getValue(
            self::XML_PATH_USERNAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_helper->getScopeConfig()->getValue(
            self::XML_PATH_PASSWORD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getTotalDiamonds()
    {


        try {
            $searchType = "White";
            $pageSize = 1;

            $requestBody =
                '"search_type": "' . $searchType . '",
				"page_number": ' . 1 . ',
				"page_size": "' . $pageSize . '"	';

            $post = '{
			"request": {
				"header": {
					"username": "' . $this->getUsername() . '",
					"password": "' . $this->getPassword() . '"
				},
				"body": {
					' . $requestBody . '
				}
			}
		}';
            $requestUrl = 'https://technet.rapaport.com/HTTP/JSON/RetailFeed/GetDiamonds.aspx';
            $request = curl_init($requestUrl);
            curl_setopt($request, CURLOPT_HEADER, 0);
            curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($request, CURLOPT_POSTFIELDS, $post);
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
            $responce = curl_exec($request);

            $results = json_decode($responce);

            curl_close($request);

            $total = $results->response->body->search_results->total_diamonds_found;
            return $total;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }

    }

    /**
     * RapnetItems
     */
    public function getRapnetItems()
    {
        $searchType = "White";
        $pageSize = 50;
        $totalNumberofDiamonds = $this->getTotalDiamonds();
        $totalLoops = ceil($totalNumberofDiamonds / 50);
        // array of curl handles
        $multiCurl = [];
        // data to be returned
        $result = [];
        // multi handle
        $mh = curl_multi_init();
        for ($i = 1; $i <= $totalLoops; $i++) {
            // URL from which data will be fetched
            $fetchURL = 'https://technet.rapaport.com/HTTP/JSON/RetailFeed/GetDiamonds.aspx';
            $requestBody =
                '"search_type": "' . $searchType . '",
				"page_number": ' . $i . ',
				"page_size": "' . $pageSize . '"	';

            $post = '{
                "request": {
                    "header": {
                        "username": "' . $this->getUsername() . '",
                        "password": "' . $this->getPassword() . '"
                    },
                    "body": {
                        ' . $requestBody . '
                    }
                }
		    }';
            $multiCurl[$i] = curl_init();
            curl_setopt($multiCurl[$i], CURLOPT_URL, $fetchURL);
            curl_setopt($multiCurl[$i], CURLOPT_HEADER, 0);
            curl_setopt($multiCurl[$i], CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($multiCurl[$i], CURLOPT_POSTFIELDS, $post);
            curl_setopt($multiCurl[$i], CURLOPT_SSL_VERIFYPEER, false);
            curl_multi_add_handle($mh, $multiCurl[$i]);
        }

        try {
            $index = null;
            do {
                curl_multi_exec($mh, $index);
            } while ($index > 0);
            // get content and remove handles
            foreach ($multiCurl as $k => $ch) {
                $result[$k] = curl_multi_getcontent($ch);
                curl_multi_remove_handle($mh, $ch);
                $results = json_decode($result[$k]);
                if ($results) {
                    if (curl_multi_errno($mh) || $results->response->header->error_code != 0) {
                        continue;
                    } else {
                        $diamonds = $results->response->body->diamonds;
                        $total = $results->response->body->search_results->total_diamonds_found;
                        $this->setRapnetDiamonds($diamonds);
                    }

                }
            }

            curl_multi_close($mh);
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }

    /**
     * @param $diamonds
     * @param $pageSize
     * @throws \Exception
     */
    public function setRapnetDiamonds($diamonds)
    {
        try {
            foreach ($diamonds as $diamondData) {
                if ($diamondData) {
                    $bulkInsert[] = [
                        'diamond_id' => $diamondData->diamond_id,
                        'diamond_shape' => $diamondData->shape,
                        'diamond_lab' => $diamondData->lab,
                        'diamond_carats' => $diamondData->size,
                        'diamond_clarity' => $diamondData->clarity,
                        'diamond_color' => $diamondData->color,
                        'diamond_cut' => $diamondData->cut,
                        'diamond_polish' => $diamondData->polish,
                        'diamond_symmetry' => $diamondData->symmetry,
                        'diamond_table_percent' => $diamondData->table_percent,
                        'diamond_depth_percent' => $diamondData->depth_percent,
                        'diamond_measurements' => $diamondData->meas_width,
                        'diamond_fluoresence' => $diamondData->fluor_intensity,
                        'diamond_certificate_num' => $diamondData->cert_num,
                        'diamond_meas_width' => $diamondData->meas_width,
                        'diamond_has_cert_file' => $diamondData->has_cert_file,
                        'diamond_price' => $diamondData->total_sales_price_in_currency,
                        'diamond_stock_num' => $diamondData->stock_num,
                        'diamond_has_image_file' => $diamondData->has_image_file,
                        'diamond_image_file_url' => $diamondData->image_file_url
                    ];
                }
            }

            $dbStorage = $this->_dbStorageFactory;
            $dbStorage->insertMultiple('codilar_rapnet', $bulkInsert);
        } catch (\Exception $e) {
            $this->_logger->critical($e);
        }
    }


    /**
     * @return $this
     */
    public function execute()
    {

        try {
            $rapnetCollection = $this->rapnetRepository->getCollection();
            /** @var \Magento\Framework\DB\Adapter\AdapterInterface $connection */
            $connection = $rapnetCollection->getConnection();
            $tableName = $rapnetCollection->getMainTable();
            $connection->truncateTable($tableName);
            $this->getRapnetItems();
            $this->_helperData->updateRapnetProductPrice();
            return $this;
        } catch (\Exception $e) {
            $this->_logger->critical($e);

        }
    }
}
