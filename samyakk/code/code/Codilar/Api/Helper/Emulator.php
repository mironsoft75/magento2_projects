<?php
/**
 * created by PhpStorm.
 * User: root
 * Date: 22/12/17
 * Time: 1:36 PM
 */

namespace Codilar\Api\Helper;


use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;

class Emulator extends AbstractHelper
{

    const AREA_FRONTEND = Area::AREA_FRONTEND;
    const AREA_ADMINHTML = Area::AREA_ADMINHTML;
    const AREA_RESTAPI = Area::AREA_WEBAPI_REST;
    const AREA_SOAPAPI = Area::AREA_WEBAPI_SOAP;
    /**
     * @var Emulation
     */
    private $emulation;

    /**
     * @var bool
     */
    private $_isEmulating;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(
        Context $context,
        Emulation $emulation,
        StoreManagerInterface $storeManager
    )
    {
        parent::__construct($context);
        $this->emulation = $emulation;
        $this->_isEmulating = false;
        $this->storeManager = $storeManager;
    }


    /**
     * @param string $area
     * @param int $storeId
     */
    public function startEmulation($area = self::AREA_FRONTEND, $storeId = null) {
        if(!$storeId) {
            $storeId = $this->getCurrentStore()->getId();
        }
        $this->emulation->startEnvironmentEmulation($storeId, $area, true);
        $this->_isEmulating = true;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getCurrentStore() {
        return $this->storeManager->getStore();
    }

    public function stopEmulation() {
        if($this->_isEmulating) {
            $this->emulation->stopEnvironmentEmulation();
        }
    }

}