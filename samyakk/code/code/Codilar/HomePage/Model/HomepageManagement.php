<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\HomePage\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Api\Api\ResponseBuilder;
use Codilar\Carousel\Api\CarouselRepositoryInterface;
use Codilar\HomePage\Api\Data\HomepageInterface;
use Codilar\HomePage\Api\HomepageManagementInterface;
use Codilar\HomePage\Model\Cache\Homepage as HomepageCache;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Webapi\Rest\Response;
use Codilar\BannerSlider\Api\SliderRepositoryInterface;
use Codilar\Category\Api\ManagementInterface as CategoryManagement;
use Codilar\Cms\Api\BlockRepositoryInterface;
use Codilar\Offers\Api\HomepageBlocksRepositoryInterface;

class HomepageManagement extends AbstractApi implements HomepageManagementInterface
{
    /**
     * @var HomepageInterface
     */
    private $homepage;
    /**
     * @var HomepageCache
     */
    private $homepageCache;
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;
    /**
     * @var CategoryManagement
     */
    private $categoryManagement;
    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;
    /**
     * @var HomepageBlocksRepositoryInterface
     */
    private $homepageBlocksRepository;
    /**
     * @var ResponseBuilder
     */
    private $responseBuilder;
    /**
     * @var CarouselRepositoryInterface
     */
    private $carouselRepository;

    /**
     * HomepageManagement constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param HomepageInterface $homepage
     * @param HomepageCache $homepageCache
     * @param SliderRepositoryInterface $sliderRepository
     * @param CategoryManagement $categoryManagement
     * @param BlockRepositoryInterface $blockRepository
     * @param HomepageBlocksRepositoryInterface $homepageBlocksRepository
     * @param ResponseBuilder $responseBuilder
     * @param CarouselRepositoryInterface $carouselRepository
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        HomepageInterface $homepage,
        HomepageCache $homepageCache,
        SliderRepositoryInterface $sliderRepository,
        CategoryManagement $categoryManagement,
        BlockRepositoryInterface $blockRepository,
        HomepageBlocksRepositoryInterface $homepageBlocksRepository,
        ResponseBuilder $responseBuilder,
        CarouselRepositoryInterface $carouselRepository
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->homepage = $homepage;
        $this->homepageCache = $homepageCache;
        $this->sliderRepository = $sliderRepository;
        $this->categoryManagement = $categoryManagement;
        $this->blockRepository = $blockRepository;
        $this->homepageBlocksRepository = $homepageBlocksRepository;
        $this->responseBuilder = $responseBuilder;
        $this->carouselRepository = $carouselRepository;
    }

    /**
     * @return \Codilar\HomePage\Api\Data\HomepageInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getHomepageData()
    {
        /** @var \Codilar\HomePage\Api\Data\HomepageInterface $homepage */
        $homepage = $this->homepageCache->getCachedData();
        if (!$homepage) {
            $homepage = $this->homepage;
            $homepage
                ->setBannerSlider($this->sliderRepository->getHomePageSlider())
                ->setCarousel($this->carouselRepository->getCarousels())
                ->setCmsBlock($this->blockRepository->getBlocks())
                ->setOfferBlocks($this->homepageBlocksRepository->getBlocks())
                ->setShopByCategory($this->categoryManagement->getHomepageCategory());
            $this->homepageCache->setCachedData($homepage);
        }
//        $homepage = $this->responseBuilder->arrayToObject($homepage, HomepageInterface::class); // TODO: cache to be implemented later
        return $this->sendResponse($homepage);
    }


}
