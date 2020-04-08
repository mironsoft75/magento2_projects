<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Model;


use Codilar\Meta\Api\Data\MetaDataInterface;
use Codilar\Meta\Api\MetaDataManagementInterface;
use Codilar\Meta\Api\Types\Pool;
use Magento\Store\Model\App\Emulation;
use Magento\Store\Model\StoreManagerInterface;

class MetaDataManagement implements MetaDataManagementInterface
{
    /**
     * @var Pool
     */
    private $metaTypesPool;
    /**
     * @var Emulation
     */
    private $emulation;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * MetaDataManagement constructor.
     * @param Pool $metaTypesPool
     * @param Emulation $emulation
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Pool $metaTypesPool,
        Emulation $emulation,
        StoreManagerInterface $storeManager
    )
    {
        $this->metaTypesPool = $metaTypesPool;
        $this->emulation = $emulation;
        $this->storeManager = $storeManager;
    }

    /**
     * @param string $type
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMetaData($type, $id)
    {
        $this->emulation->startEnvironmentEmulation($this->storeManager->getStore()->getId(), \Magento\Framework\App\Area::AREA_FRONTEND, true);
        $response = $this->metaTypesPool->getTypeInstance($type)->getMetaTypeData($id);
        $this->emulation->stopEnvironmentEmulation();
        return $response;
    }
}
