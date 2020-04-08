<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Header\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Header\Api\Data\HeaderInterface;
use Codilar\Header\Api\HeaderManagementInterface;
use Codilar\Logo\Api\LogoRepositoryInterface;
use Codilar\MegaMenu\Api\CategoryRepositoryInterface;
use Codilar\Store\Api\StoreRepositoryInterface;
use Codilar\Wishlist\Api\WishlistRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Webapi\Rest\Response;
use Magento\Store\Model\ScopeInterface;
use Tagalys\Sync\Helper\Configuration;

class HeaderManagement extends AbstractApi implements HeaderManagementInterface
{

    const ISTAGALYSSEARCHXMLPATH = "tagalys_search/general/active";

    /**
     * @var HeaderInterface
     */
    private $header;
    /**
     * @var LogoRepositoryInterface
     */
    private $logoRepository;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    /**
     * @var WishlistRepositoryInterface
     */
    private $wishlistRepository;
    /**
     * @var StoreRepositoryInterface
     */
    private $storeRepository;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var Configuration
     */
    private $tagalysConfiguration;

    /**
     * HeaderManagement constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param HeaderInterface $header
     * @param LogoRepositoryInterface $logoRepository
     * @param CategoryRepositoryInterface $categoryRepository
     * @param WishlistRepositoryInterface $wishlistRepository
     * @param StoreRepositoryInterface $storeRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Configuration $tagalysConfiguration
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        HeaderInterface $header,
        LogoRepositoryInterface $logoRepository,
        CategoryRepositoryInterface $categoryRepository,
        WishlistRepositoryInterface $wishlistRepository,
        StoreRepositoryInterface $storeRepository,
        ScopeConfigInterface $scopeConfig,
        Configuration $tagalysConfiguration
    ) {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->header = $header;
        $this->logoRepository = $logoRepository;
        $this->categoryRepository = $categoryRepository;
        $this->wishlistRepository = $wishlistRepository;
        $this->storeRepository = $storeRepository;
        $this->scopeConfig = $scopeConfig;
        $this->tagalysConfiguration = $tagalysConfiguration;
    }

    /**
     * @return \Codilar\Header\Api\Data\HeaderInterface
     */
    public function getHeaderData()
    {
        $header = $this->header;
        $header->setLogo($this->logoRepository->getLogo())
                ->setMegaMenu($this->categoryRepository->getMenuData())
                ->setWishlist($this->wishlistRepository->getWishlist())
                ->setStores($this->storeRepository->getStores())
                ->setisTagalysSearch($this->getIsTagalysSearchFromConfig());
        return $header;
    }

    /**
     * @return bool
     */
    protected function getIsTagalysSearchFromConfig()
    {
        if ($this->tagalysConfiguration->getConfig("module:search:enabled") === "1") {
            $tagalysSearchConfigurationStatus = true;
        } else {
            $tagalysSearchConfigurationStatus = false;
        }
        return (bool)$this->scopeConfig->getValue(
            self::ISTAGALYSSEARCHXMLPATH,
            ScopeInterface::SCOPE_STORE
        ) && $tagalysSearchConfigurationStatus;
    }
}
