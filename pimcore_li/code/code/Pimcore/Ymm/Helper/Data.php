<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 5:59 PM
 * Filename: Data.php
 */

namespace Pimcore\Ymm\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\UrlInterface;
use Pimcore\Ymm\Api\YmmApiManagementInterface;

/**
 * Class Data
 * @package Pimcore\Ymm\Helper
 */
class Data extends AbstractHelper
{
    CONST YEAR_COL = 'year_id';
    CONST MAKE_COL = 'make_name';
    CONST MODEL_COL = 'model_name';
    const CATEGORY_ACTION_NAME = ['amshopby_index_index', 'catalog_category_view'];
    /**
     * @var YmmApiManagementInterface
     */
    private $_ymmApiManagement;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var UrlInterface
     */
    private $url;


    public function __construct(
        Context $context,
        Session $session,
        RequestInterface $request,
        UrlInterface $url
    )
    {
        parent::__construct($context);
        $this->session = $session;
        $this->request = $request;
        $this->url = $url;
    }


    public function getYmmSessionData($key)
    {
        return $this->session->getData($key) ?? NULL;
    }

    public function isYmmSelected()
    {
        return !empty(array_filter($this->getYmmValues()));
    }

    public function clearSelectedYmm()
    {
        $this->session->getData(self::MAKE_COL, 1);
        $this->session->getData(self::MODEL_COL, 1);
        $this->session->getData(self::YEAR_COL, 1);
    }

    public function getYmmValues()
    {
        $makeName = $this->request->getParam('make_name') ?? $this->getYmmSessionData(self::MAKE_COL);
        $modelName = $this->request->getParam('model_name') ?? $this->getYmmSessionData(self::MODEL_COL);
        $yearId = $this->request->getParam('year_id') ?? $this->getYmmSessionData(self::YEAR_COL);
        $params = [self::MAKE_COL => $makeName, self::MODEL_COL => $modelName, self::YEAR_COL => $yearId];
        return $params;
    }


    public function getAllProductsUrl()
    {
        return $this->url->getUrl('all-products', ['_query' => $this->getYmmValues()]);
    }

    public function isMakeandModelPage()
    {
        if (!in_array($this->request->getFullActionName(), self::CATEGORY_ACTION_NAME)) {
            return false;
        }
        $params = $this->request->getParams();
        $yearId = $params[\Pimcore\Ymm\Model\YmmApiManagement::YEAR_COL] ?? NULL;
        $makeName = $params[\Pimcore\Ymm\Model\YmmApiManagement::MAKE_COL] ?? NULL;
        $modelName = $params[\Pimcore\Ymm\Model\YmmApiManagement::MODEL_COL] ?? NULL;
        if ($makeName && $modelName && !$yearId) {
            return true;
        }
        return false;
    }

    public function getSelectedYmm($key)
    {
        return $this->request->getParam($key) ?? NULL;
    }


}