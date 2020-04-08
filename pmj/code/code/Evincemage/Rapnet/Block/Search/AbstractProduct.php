<?php
/**
 * @author Evince Team
 * @copyright Copyright © 2018 Evince (http://evincemage.com/)
 */

namespace Evincemage\Rapnet\Block\Search;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Evincemage\Rapnet\Helper\Data as Helper;
use Magento\Framework\Session\SessionManagerInterface;

class AbstractProduct extends Template
{
    protected $sessionManager;

    protected $helper;

    public function __construct(
        Context $context,
        SessionManagerInterface $sessionManager,
        Helper $helper,
        array $data = []
    )
    {
        $this->sessionManager = $sessionManager;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function getSearchUrl()
    {
        return $this->getUrl('diamondsearch/index/rapnetsearch', ['_secure' => true]);
    }

    public function getProductCollection()
    {
        $request = $this->getRequest()->getParams();
        return $this->getDiamonds($request);
    }

    public function getUrlString()
    {
       return $this->getRequest()->getUriString();
    }

    protected function getDiamonds($request)
    {
        if ($request != NULL) {
            $request = $this->validate($request);
        } else {
            $diamond = [
                'meta' => ['code' => 400, 'message' => __('No arguments supplied.')],
                'data' => [],
                'pagination' => []
            ];
            return $diamond;
        }

        if (!is_array($request)) {
            $diamond = [
                'meta' => ['code' => 400, 'message' => $request],
                'data' => [],
                'pagination' => []
            ];
            return $diamond;
        }

        //Get the attribute labels and add it to an array to filter.
        $data["page"] = 1;
        if ($request['by'] == '') {
            $data['by'] = 'price';

            $this->sessionManager->seSort('price');
        }

        $sort = $this->sessionManager->getSort();
        $dir = $this->sessionManager->getDir();

        foreach ($request as $key => $param) {

            if ($key != "by" && $key != "c") {
                $data[$key] = $param;

            } else if ($key == "by") {
                if ($sort == $param) {

                    if ($dir == "ASC" && $request['c'] == 'y') {
                        $dir = "DESC";
                        $this->sessionManager->setDir($dir);
                    } else {
                        if ($request['c'] == '') {
                            $data[$key] = $param;
                            continue;
                        } else {
                            $dir = "ASC";
                            $this->sessionManager->setDir($dir);
                        }
                    }
                } else {
                    $dir = "ASC";
                    $this->sessionManager->setDir($dir);
                }
                if (trim($param) == '') {
                    $param = 'price';
                }
                $data[$key] = $param;
                $this->sessionManager->setSort($param);
            }
        }

        // Convert the Shapes list into rapnet form
        $shapeValue = [];

        if (array_key_exists('diamond_shape', $data)) {

            foreach ($data["diamond_shape"] as $value) {
                $shapeValue[] = strtolower("\"$value\"");
            }
            $shapesContent = '[' . implode(',', $shapeValue) . ']';

        } else {
            $shape = strtolower(trim($this->sessionManager->getShape()));
            $shapesContent = '[]';
            if ($shape != NULL) {
                $shapesContent = '[' . '"' . $shape . '"' . ']';
            }
        }

        // Convert the Certificate array into rapnet form
        $certificate = [];
        $certificatesContent = '[]';

        if (array_key_exists('diamond_certificates', $data)) {

            foreach ($data["diamond_certificates"] as $values) {

                if ($values == "EGL USA") {
                    $certificate[] = "\"egl_usa\"";
                } else if ($values == "EGL Israel") {
                    $certificate[] = "\"egl_israel\"";
                } else {
                    $certificate[] = strtolower("\"$values\"");
                }
            }
            $certificatesContent = '[' . implode(',', $certificate) . ']';
        }

        // Convert the Fluorescence list into rapnet form

        $fluorescence = [];

        $fluorescenceContent = '[]';
        if (array_key_exists('diamond_fluorescence', $data)) {

            foreach ($data["diamond_fluorescence"] as $value) {
                $fluorescence[] = strtolower("\"$value\"");
            }
            $fluorescenceContent = '[' . implode(',', $fluorescence) . ']';
        }

        // Convert the Fancy Color list into rapnet form

        $fancycolor = [];
        $fancycolorContent = '[]';

        if (array_key_exists('diamond_fancycolor', $data)) {

            foreach ($data["diamond_fancycolor"] as $value) {
                $fancycolor[] = strtolower("\"$value\"");
            }
            $fancycolorContent = '[' . implode(',', $fancycolor) . ']';
        }

        if(!array_key_exists('diamond_fcintensity', $data)){
            $data["diamond_fcintensity"] = [];
        }

        // Create the data array to sumbit to rapnet
        $requestData = [
            'shapes' => $shapesContent,
            'search_type' => $data["search_type"],
            'fluorescence_intensities' => $fluorescenceContent,
            'size_from' => floatval(reset($data["diamond_carats"])),
            'size_to' => floatval(end($data["diamond_carats"])),
            'color_from' => reset($data["diamond_color"]),
            'color_to' => end($data["diamond_color"]),
            'clarity_from' => reset($data["diamond_clarity"]),
            'clarity_to' => end($data["diamond_clarity"]),
            'cut_from' => reset($data["diamond_cut"]),
            'cut_to' => end($data["diamond_cut"]),
            'polish_from' => reset($data["diamond_polish"]),
            'polish_to' => end($data["diamond_polish"]),
            'symmetry_from' => reset($data["diamond_symmetry"]),
            'symmetry_to' => end($data["diamond_symmetry"]),
            'price_total_from' => intval(reset($data["price"])),
            'price_total_to' => intval(end($data["price"])),
            'diamond_table_from' => intval(reset($data["diamond_table"])),
            'diamond_table_to' => intval(end($data["diamond_table"])),
            'depth_percent_from' => intval(reset($data["diamond_depth"])),
            'depth_percent_to' => intval(end($data["diamond_depth"])),
            'fluorescence_intensities_from' => $fluorescenceContent,
            'diamond_fancycolor' => $fancycolorContent,
            'diamond_fcintensity_from' => reset($data["diamond_fcintensity"]),
            'diamond_fcintensity_to' => end($data["diamond_fcintensity"]),
            'labs' => $certificatesContent,
            'page_number' => intval($data['page']),
            'page_size' => 10,
            'sort_by' => $data['by'],
            'sort_direction' => $dir
        ];
        // Submit data array to rapnet through the model

        $result = $this->helper->sendRequest($requestData);

        $num = ceil($result['total'] / 10);
        $prev = null;
        $next = null;

        $url = $this->getCurrentUrl();;
        if ($data['page'] == 1) {

            if (strpos($url, '&page=1') !== false) {
                $url = substr($url, 0, -1);

                if ($data['page'] < $num) {
                    $next = $url . ((string)$data['page'] + 1);
                }
            } else {
                if ($data['page'] < $num) {
                    $next = $url . "&page=" . ((string)$data['page'] + 1);
                }
            }
        } else if ($data['page'] > 1) {

            $url = strstr(substr($url, 0, -1), "&page=", true);
            if (strpos($url, '&page') !== true) {

                $prev = $url . "&page=" . ((string)$data['page'] - 1);
                if ($data['page'] < $num) {
                    $next = $url . "&page=" . ((string)$data['page'] + 1);
                }
            }
        }

        if ($result['diamonds'] != NULL || $result['total'] != 0) {

            $count = 0;
            if ($data['page'] > 1) {
                $count = 10 * ($data['page'] - 1);
            }

            $diamond = [
                'meta' => ['code' => 200],
                'data' => $result['diamonds'],
                'pagination' => [
                    'count'     => $count,
                    'limit'     => count($result['diamonds']),
                    'total'     => $result['total'],
                    'prev page' => $prev,
                    'next page' => $next
                ]
            ];
        } else {

            $diamond = [
                'meta' =>['code' => 404, 'message' => "No Product Found"],
                'data' => [],
                'pagination' =>['total' => $result['total']]
            ];
        }
        return $diamond;

    }

    private function validate($query)
    {
        //Check both keys and values for proper information
        foreach ($query as $key => $value) {
            if ($key == 'order' || $key == 'search_type' || $key == 'dir' ||
                $key == 'page' || $key == 'sku' || $key == 'by' || $key == 'c') {
                continue;
            }

            if (!is_array($value)) {
                return __('%1 does not have proper format. Please refer to documentation for proper keys.', $key);

            } else {

                switch ($key) {
                    case 'price':
                        foreach ($value as $k => $v) {
                            if ($k != 'from' && $k != 'to') {

                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);
                            } else {

                                $errorMsg = __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                if (!is_numeric($v)) {
                                    return $errorMsg;
                                } else {
                                    $v = (float)$v;
                                    if ($v < 0 || $v > 5000000) {
                                        return $errorMsg;
                                    }
                                }
                            }
                        }
                        break;

                    case 'diamond_table':

                        foreach ($value as $k => $v) {

                            if ($k != 'from' && $k != 'to') {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else {

                                $error = __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                if (!is_numeric($v)) {
                                    return $error;
                                } else {
                                    if ($v < 0 || $v > 100) {
                                        return $error;
                                    }
                                }
                            }
                        }

                        break;


                    case 'diamond_depth':

                        foreach ($value as $k => $v) {

                            if ($k != 'from' && $k != 'to') {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);
                            } else {

                                $error = __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                if (!is_numeric($v)) {
                                    return $error;

                                } else {
                                    if ($v < 0 || $v > 100) {
                                        return $error;
                                    }
                                }
                            }
                        }
                        break;

                    case 'diamond_shape':

                        $attribute = ["round", "princess", "emerald", "radiant", "cushion", "pear", "marquise",
                            "oval", "asscher", "heart"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_fluorescence':

                        $attribute = array("none", "very slight", "faint", "medium", "strong", "very strong");

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_certificates':

                        $attribute = ["gia", "egl usa","egl israel", "igi", "ags", "hrd"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_carats':

                        foreach ($value as $k => $v) {

                            if ($k != 'from' && $k != 'to') {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else {

                                $error = __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);

                                if (!is_numeric($v)) {

                                    return $error;
                                } else {

                                    $v = (float)$v;
                                    if ($v < 0.18 || $v > 10) {
                                        return $error;
                                    }
                                }
                            }
                        }
                        break;

                    case 'diamond_cut':

                        $attribute = ["excellent", "very good", "good", "fair"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {

                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_color':

                        $attribute = ["d", "e", "f", "g", "h", "i", "j", "k", "l", "m"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {

                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_clarity':

                        $attribute = ["if", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2", "si3", "i1"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);

                                }
                            }
                        }
                        break;

                    case 'diamond_polish':

                        $attribute = ["excellent", "very good", "good", "fair"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_symmetry':

                        $attribute = ["excellent", "very good", "good", "fair"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_fancycolor':

                        $attribute = ["yellow", "pink", "blue", "red", "green", "purple", "orange", "violet",
                            "gray", "black", "brown", "champagne", "cognac", "chameleon", "white", "other"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    case 'diamond_fcintensity':

                        $attribute = ["faint", "very light", "light", "fancy light", "fancy", "fancy dark",
                            "fancy intense", "fancy vivid", "fancy Deep"];

                        foreach ($value as $k => $v) {

                            if (!is_numeric($k) || (int)$k > count($value)) {
                                return __('%1 [%2] does not exist. Please refer to documentation for proper keys.', $key, $k);

                            } else if (!is_numeric($v)) {

                                $v = strtolower($v);
                                if (!in_array($v, $attribute)) {
                                    return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                                }
                            }
                        }
                        break;

                    default:

                        return __('%1 does not exist. Please refer to documentation for proper keys.');
                        break;
                }
            }
        }

        $block = $this->getLayout()->getBlockSingleton('Evincemage\Rapnet\Block\Index');

        $diamondAttributes = $block->getDiamondAttributes();

        $numbers = [];

        foreach ($query as $key => $value) {

            if ($key != "price" && $key != "diamond_table" && $key != "diamond_depth" &&
                $key != "sku" && $key != "diamond_carats" && $key != "page" &&
                $key != "order" && $key != "dir") {

                if (is_array($value)) {

                    foreach ($value as $k => $v) {

                        foreach ($diamondAttributes['rapnet_' . $key]['options'] as $attr) {

                            if ($v == $attr['label'] || $v == strtolower($attr['label'])) {
                                $query[$key][$k] = $attr['value'];
                                $attr['selected'] = 'checked';
                            }
                            $numbers[] = $attr['value'];
                        }

                        if (is_numeric($v) && !in_array($v, $numbers)) {
                            return __('%1 is an incorrect value for %2 key. Please refer to documentation for proper value types.', $v, $key);
                        }
                        unset($numbers);
                    }
                }
            }
        }
        return $query;
    }
}