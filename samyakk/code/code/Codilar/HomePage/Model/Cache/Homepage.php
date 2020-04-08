<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\HomePage\Model\Cache;

use Codilar\Api\Api\ResponseBuilder;
use Codilar\HomePage\Api\Data\HomepageInterface;
use Codilar\HomePage\Api\Data\HomepageInterfaceFactory;
use Magento\Framework\Cache\Frontend\Decorator\TagScope;
use Magento\Framework\App\Cache\Type\FrontendPool;
use Magento\Framework\DataObject;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\Webapi\ServiceOutputProcessor;
use Magento\Store\Model\StoreManagerInterface;

class Homepage extends TagScope
{
    const TYPE_IDENTIFIER = 'codilar_homepage_cache';
    const CACHE_TAG = 'CODILAR_HOMEPAGE_CACHE';
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var HomepageInterfaceFactory
     */
    private $homepageInterfaceFactory;
    /**
     * @var SerializerInterface
     */
    private $serializer;
    /**
     * @var DataObjectFactory
     */
    private $dataObjectFactory;
    /**
     * @var ServiceOutputProcessor
     */
    private $serviceOutputProcessor;
    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;

    /**
     * Homepage constructor.
     * @param FrontendPool $frontendPool
     * @param StoreManagerInterface $storeManager
     * @param HomepageInterfaceFactory $homepageInterfaceFactory
     * @param SerializerInterface $serializer
     * @param ServiceOutputProcessor $serviceOutputProcessor
     * @param DataObjectFactory $dataObjectFactory
     * @param ResponseBuilder $responseBuilder
     */
    public function __construct(
        FrontendPool $frontendPool,
        StoreManagerInterface $storeManager,
        HomepageInterfaceFactory $homepageInterfaceFactory,
        SerializerInterface $serializer,
        ServiceOutputProcessor $serviceOutputProcessor,
        DataObjectFactory $dataObjectFactory,
        ResponseBuilder $responseBuilder
    )
    {
        parent::__construct($frontendPool->get(self::TYPE_IDENTIFIER), self::CACHE_TAG);
        $this->storeManager = $storeManager;
        $this->homepageInterfaceFactory = $homepageInterfaceFactory;
        $this->serializer = $serializer;
        $this->dataObjectFactory = $dataObjectFactory;
        $this->serviceOutputProcessor = $serviceOutputProcessor;
        $this->responseBuilder = $responseBuilder;
    }

    /**
     * @return bool|HomepageInterface
     */
    public function getCachedData()
    {
        $homepageCache = $this->load($this->getCacheIdentifier());
        if ($homepageCache) {
            /** @var HomepageInterface $homepage */
            $homepage = unserialize($homepageCache);
            return $homepage;
        }
        return false;
    }

    /**
     * @param HomepageInterface $homepage
     * @return $this
     */
    public function setCachedData($homepage) {
        $homepage = $this->responseBuilder->objectToArray($homepage, HomepageInterface::class);
        //$this->save(serialize($homepage), $this->getCacheIdentifier()); // TODO: cache to be implemented later
        return $this;
    }

    /**
     * @return void
     */
    public function deleteCachedData()
    {
        $this->remove($this->getCacheIdentifier());
    }

    /**
     * @return string
     */
    protected function getCacheIdentifier() {
        try {
            $store = $this->storeManager->getStore()->getCode();
        } catch (NoSuchEntityException $e) {
            $store = 'default';
        }
        return self::TYPE_IDENTIFIER."_".$store."_homepage";
    }

    /**
     * @param array $array
     * @return DataObject
     */
    protected function getNewDataObject($array)
    {
        return $this->dataObjectFactory->create()->setData($array);
    }

}
