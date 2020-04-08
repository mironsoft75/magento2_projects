<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Helper;

use Magento\Framework\App\Helper\Context;
use Psr\Log\LoggerInterface;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_RAPNET_ENABLED = 'rapnet/settings/rapnet';
    const XML_PATH_USERNAME = 'rapnet/settings/username';
    const XML_PATH_PASSWORD = 'rapnet/settings/password';
    const XML_PATH_SLIDER_COLOR = 'rapnet/settings/slider_colour';
    const XML_PATH_HOVER_COLOR = 'rapnet/settings/hover_colour';

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Data constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * @return bool
     */
    public function isRapnet()
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_RAPNET_ENABLED,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_USERNAME,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_PASSWORD,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getSiderColor()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_SLIDER_COLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @return mixed
     */
    public function getHoverColor()
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_HOVER_COLOR,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * @param $requestParam
     * @return array
     */
    public function sendRequest($requestParam)
    {

        if ($requestParam['search_type'] == 'Glowing') {
            $requestParam['search_type'] = 'White';
        }

        if ($requestParam['search_type'] == 'Fancy') {
            $requestBody =
                '"search_type": "' . $requestParam['search_type'] . '",
				"shapes": ' . $requestParam['shapes'] . ',
				"clarity_from": "' . $requestParam['clarity_from'] . '",
				"clarity_to": "' . $requestParam['clarity_to'] . '",
				"fancy_colors": ' . $requestParam['diamond_fancycolor'] . ',
				"fancy_color_intensity_from": "' . $requestParam['diamond_fcintensity_from'] . '",
				"fancy_color_intensity_to": "' . $requestParam['diamond_fcintensity_to'] . '",
				"cut_from": "' . $requestParam['cut_from'] . '",
				"cut_to": "' . $requestParam['cut_to'] . '",
				"polish_from": "' . $requestParam['polish_from'] . '",
				"polish_to": "' . $requestParam['polish_to'] . '",
				"symmetry_from": "' . $requestParam['symmetry_from'] . '",
				"symmetry_to": "' . $requestParam['symmetry_to'] . '",
				"table_percent_from": "' . $requestParam['diamond_table_from'] . '",
				"table_percent_to": "' . $requestParam['diamond_table_to'] . '",
				"depth_percent_from": "' . $requestParam['depth_percent_from'] . '",
				"depth_percent_to": "' . $requestParam['depth_percent_to'] . '",
				"labs": ' . $requestParam['labs'] . ',
				"price_total_from": ' . intval($requestParam['price_total_from'] / 60) . ',
				"price_total_to": ' . intval($requestParam['price_total_to'] / 60) . ',
				"page_number": ' . $requestParam['page_number'] . ',
				"page_size": ' . $requestParam['page_size'] . ',
				"sort_by": "' . $requestParam['sort_by'] . '",
				"sort_direction": "' . $requestParam['sort_direction'] . '"	';
        } else {
            $requestBody =
                '"search_type": "' . $requestParam['search_type'] . '",
				"shapes": ' . $requestParam['shapes'] . ',
				"fluorescence_intensities": ' . $requestParam['fluorescence_intensities'] . ',
				"size_to": ' . $requestParam['size_to'] . ',
				"size_from": ' . $requestParam['size_from'] . ',
				"color_from": "' . $requestParam['color_from'] . '",
				"color_to": "' . $requestParam['color_to'] . '",
				"clarity_from": "' . $requestParam['clarity_from'] . '",
				"clarity_to": "' . $requestParam['clarity_to'] . '",					
				"cut_from": "' . $requestParam['cut_from'] . '",
				"cut_to": "' . $requestParam['cut_to'] . '",
				"polish_from": "' . $requestParam['polish_from'] . '",
				"polish_to": "' . $requestParam['polish_to'] . '",
				"symmetry_from": "' . $requestParam['symmetry_from'] . '",
				"symmetry_to": "' . $requestParam['symmetry_to'] . '",
				"table_percent_from": "' . $requestParam['diamond_table_from'] . '",
				"table_percent_to": "' . $requestParam['diamond_table_to'] . '",
				"depth_percent_from": "' . $requestParam['depth_percent_from'] . '",
				"depth_percent_to": "' . $requestParam['depth_percent_to'] . '",					
				"labs": ' . $requestParam['labs'] . ',
				"price_total_from": ' . intval($requestParam['price_total_from'] / 60) . ',
				"price_total_to": ' . intval($requestParam['price_total_to'] / 60) . ',
				"page_number": ' . $requestParam['page_number'] . ',
				"page_size": ' . $requestParam['page_size'] . ',
				"sort_by": "' . $requestParam['sort_by'] . '",
				"sort_direction": "' . $requestParam['sort_direction'] . '"	';
        }

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
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $responce = curl_exec($request);

        $results = json_decode($responce);
        if (curl_errno($request) || $results->response->header->error_code != 0) {
            $this->logger->error('Rapnet: '.$results->response->header->error_message);
            return $returnData = array('diamonds' => array(), 'total' => 0);
        }

        curl_close($request);

        $diamonds = $results->response->body->diamonds;
        $total = $results->response->body->search_results->total_diamonds_found;
        $returnData = array('diamonds' => $diamonds, 'total' => $total);

        return $returnData;
    }

    /**
     * @param $id
     * @return array
     */
    public function getDiamondById($id)
    {

        $post = '{
			"request": {
				"header": {
					"username": "' . $this->getUsername() . '",
					"password": "' . $this->getPassword() . '"
				},
				"body": {
					"diamond_id": ' . $id . ',
					"eye_cleans": "Yes" 
				}
			}
		}';

        $requestUrl = 'https://technet.rapaport.com/HTTP/JSON/RetailFeed/GetSingleDiamond.aspx';

        $request = curl_init($requestUrl);
        curl_setopt($request, CURLOPT_HEADER, 0);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $post);
        curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE);
        $responce = curl_exec($request);

        $results = json_decode($responce);
        if (curl_errno($request)) {
            return [];
        }
        curl_close($request);

        $url = file_get_contents('http://www.diamondselections.com/GetImage.aspx?diamondid=' . $results->response->body->diamond->diamond_id);

        preg_match_all('/<img[^>]+>/i', $url, $imagedata);
        preg_match( '@src="([^"]+)"@' , $imagedata[0][0], $src );

        $diamondData = $results->response->body->diamond;

        if (!empty($src) && $imagedata[0][0]) {
            $diamondData->image = $imagedata[0][0];
        }
        $diamondData->seller = $results->response->body->seller;

        return $diamondData;

    }
}