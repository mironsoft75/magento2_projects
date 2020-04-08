<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Model\Api;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\BannerSlider\Api\Data\SliderInterface;
use Codilar\BannerSlider\Api\SliderRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;
use Magestore\Bannerslider\Model\ResourceModel\Slider\Collection;
use Magestore\Bannerslider\Model\ResourceModel\Slider\CollectionFactory;
use Magestore\Bannerslider\Model\SliderFactory as ObjectFactory;
use Magestore\Bannerslider\Model\ResourceModel\Slider as ObjectResource;
use Magestore\Bannerslider\Model\ResourceModel\Banner\CollectionFactory as BannerCollectionFactory;

class SliderRepository extends AbstractApi implements SliderRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var ObjectResource
     */
    private $objectResource;
    /**
     * @var ObjectFactory
     */
    private $objectFactory;
    /**
     * @var BannerCollectionFactory
     */
    private $bannerCollectionFactory;

    /**
     * SliderRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param CollectionFactory $collectionFactory
     * @param ObjectResource $objectResource
     * @param ObjectFactory $objectFactory
     * @param BannerCollectionFactory $bannerCollectionFactory
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        CollectionFactory $collectionFactory,
        ObjectResource $objectResource,
        ObjectFactory $objectFactory,
        BannerCollectionFactory $bannerCollectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->objectResource = $objectResource;
        $this->objectFactory = $objectFactory;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
    }


    /**
     * @param $sliderId
     * @return SliderInterface|\Magestore\Bannerslider\Model\Slider
     * @throws NoSuchEntityException
     */
    public function getById($sliderId)
    {
        $object = $this->objectFactory->create();
        $this->objectResource->load($object, $sliderId);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $sliderId));
        }
        return $object;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create()->addFieldToFilter("status", 1)->addFieldToFilter("show_in_homepage", 1);
    }

    /**
     * @return \Codilar\BannerSlider\Api\Data\SliderInterface[]
     */
    public function getHomePageSlider()
    {
        $data = [];
        $sliderCollection = $this->getCollection();
        if ($sliderCollection->getSize()) {
            $data = $sliderCollection->getItems();
        }
        return $this->sendResponse($data);
    }
}
