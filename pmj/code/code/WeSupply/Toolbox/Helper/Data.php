<?php

namespace WeSupply\Toolbox\Helper;

use WeSupply\Toolbox\Model\OrderInfoBuilder;
/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    const WESUPPLY_DOMAIN = 'labs.wesupply.xyz';

    /**
     * @var \WeSupply\Toolbox\Api\WeSupplyApiInterface
     */
    protected $weSupplyApi;

    /**
     * Data constructor.
     * @param \WeSupply\Toolbox\Api\WeSupplyApiInterface $weSupplyApi
     */
    public function __construct(
        \WeSupply\Toolbox\Api\WeSupplyApiInterface $weSupplyApi,
        \Magento\Framework\App\Helper\Context $context
        )
    {
        parent::__construct($context);
        $this->weSupplyApi = $weSupplyApi;
     }


    /**
     * @return mixed
     */
    public function getWeSupplyEnabled()
    {
        return $this->scopeConfig->getValue('wesupply_api/integration/wesupply_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getGuid()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_2/access_key', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getClientName()
    {
        return $this->getWeSupplySubDomain();
    }

    /**
     * @return int
     */
    public function getBatchSize()
    {
        //return $this->scopeConfig->getValue('wesupply_api/massupdate/batch_size', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return 0;
    }

    /**
     * @return mixed
     */
    public function getWeSupplyDomain()
    {
        return self::WESUPPLY_DOMAIN;
    }

    /**
     * @return mixed
     */
    public function getWeSupplySubDomain()
    {
       return $this->scopeConfig->getValue('wesupply_api/step_1/wesupply_subdomain', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @return mixed
     */
    public function getEnabledNotification()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_4/checkout_page_notification', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @return mixed
     */
    public function getNotificationDesign()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_4/design_notification', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @return mixed
     */
    public function getNotificationBoxType()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_4/notification_type', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return mixed
     */
    public function getEnableWeSupplyOrderView()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_3/wesupply_order_view_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @return mixed
     */
    public function getWeSupplyApiClientId()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_1/wesupply_client_id', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @return mixed
     */
    public function getWeSupplyApiClientSecret()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_1/wesupply_client_secret', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @return mixed
     */
    public function getWeSupplyOrderViewEnabled()
    {
        return $this->scopeConfig->getValue('wesupply_api/step_3/wesupply_order_view_enabled', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }


    /**
     * @param $orders
     * @return string
     */
    public function externalOrderIdString($orders)
    {
        $arrayOrders = $orders->toArray();

        $externalOrderIdString = implode(',', array_map(function($singleOrderArray) {
            return $singleOrderArray['increment_id'];
        }, $arrayOrders['items']));

        return $externalOrderIdString;
    }


    /**
     * @param $orders
     * @return string
     */
    public function internalOrderIdString($orders)
    {
        $arrayOrders = $orders->toArray();

        $externalOrderIdString = implode(',', array_map(function($singleOrderArray) {
            return OrderInfoBuilder::PREFIX.$singleOrderArray['entity_id'];
        }, $arrayOrders['items']));

        return $externalOrderIdString;
    }


    /**
     * maps the Wesupply Api Response containing links to each order, to an internal array
     */
    public function getGenerateOrderMap($orders)
    {
        $orderIds = $this->externalOrderIdString($orders);
        try{
            $apiPath = $this->getWeSupplySubDomain().'.'.$this->getWeSupplyDomain().'/api/';
            $this->weSupplyApi->setApiPath($apiPath);
            $this->weSupplyApi->setApiClientId($this->getWeSupplyApiClientId());
            $this->weSupplyApi->setApiClientSecret($this->getWeSupplyApiClientSecret());

            $result = $this->weSupplyApi->weSupplyInterogation($orderIds);
        }catch(\Exception $e){
            echo $e->getMessage();
        }

        return $result;
    }


    /**
     * @param $string
     * @return float|int
     */
    public function strbits($string)
    {
        return (strlen($string)*8);
    }

    /**
     * @param $bytes
     * @return string
     */
    public function formatSizeUnits($bytes)
    {

        /**
         * transforming bytes in MB
         */
        if ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2);
        }
        else
        {
            return 0;
        }


        return $bytes;
    }
}
