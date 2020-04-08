<?php
/**
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model;


use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Carousel\Api\CarouselItemRepositoryInterface;
use Codilar\Carousel\Api\CarouselRepositoryInterface;
use Codilar\Carousel\Api\Data\CarouselInterface;
use Codilar\Carousel\Api\Data\CarouselInterfaceFactory;
use Codilar\Carousel\Api\Data\CarouselItemLinkInterfaceFactory;
use Codilar\Carousel\Api\Data\CarouselItemsInterface;
use Codilar\Carousel\Api\Data\CarouselItemsInterfaceFactory;
use Codilar\Carousel\Helper\Filter;
use Codilar\Carousel\Model\ResourceModel\Carousel\Collection;
use Codilar\Carousel\Model\ResourceModel\Carousel\CollectionFactory;
use Codilar\Carousel\Model\Carousel as Model;
use Codilar\Carousel\Model\ResourceModel\Carousel as ResourceModel;
use Codilar\Carousel\Model\CarouselFactory as ModelFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Webapi\Rest\Response;

class CarouselRepository extends AbstractApi implements CarouselRepositoryInterface
{
    /**
     * @var ResourceModel
     */
    private $resourceModel;
    /**
     * @var ModelFactory
     */
    private $modelFactory;
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var CarouselInterfaceFactory
     */
    private $carouselInterfaceFactory;
    /**
     * @var CarouselItemRepositoryInterface
     */
    private $carouselItemRepository;
    /**
     * @var CarouselItemInterfaceFactory
     */
    private $carouselItemInterfaceFactory;
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var CarouselItemLinkInterfaceFactory
     */
    private $carouselItemLinkInterfaceFactory;

    /**
     * TimeslotRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param ResourceModel $resourceModel
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param CarouselInterfaceFactory $carouselInterfaceFactory
     * @param CarouselItemRepositoryInterface $carouselItemRepository
     * @param CarouselItemsInterfaceFactory $carouselItemInterfaceFactory
     * @param Filter $filter
     * @param CarouselItemLinkInterfaceFactory $carouselItemLinkInterfaceFactory
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        CarouselInterfaceFactory $carouselInterfaceFactory,
        CarouselItemRepositoryInterface $carouselItemRepository,
        CarouselItemsInterfaceFactory $carouselItemInterfaceFactory,
        Filter $filter,
        CarouselItemLinkInterfaceFactory $carouselItemLinkInterfaceFactory
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
        $this->carouselInterfaceFactory = $carouselInterfaceFactory;
        $this->carouselItemRepository = $carouselItemRepository;
        $this->carouselItemInterfaceFactory = $carouselItemInterfaceFactory;
        $this->filter = $filter;
        $this->carouselItemLinkInterfaceFactory = $carouselItemLinkInterfaceFactory;
    }


    /**
     * @param string $value
     * @param string $field
     * @return Model
     * @throws NoSuchEntityException
     */
    public function load($value, $field = null)
    {
        $model = $this->create();
        $this->resourceModel->load($model, $value, $field);
        if(!$model->getId()) {
            throw NoSuchEntityException::singleField($field, $value);
        }
        return $model;
    }

    /**
     * @return Model
     */
    public function create()
    {
        return $this->modelFactory->create();
    }

    /**
     * @param Model $model
     * @return Model
     * @throws AlreadyExistsException
     */
    public function save(Model $model)
    {
        $this->resourceModel->save($model);
        return $model;
    }

    /**
     * @param Model $model
     * @return $this
     * @throws LocalizedException
     */
    public function delete(Model $model)
    {
        try {
            $this->resourceModel->delete($model);
        } catch (\Exception $exception) {
            throw new LocalizedException(__("Error deleting Model with Id : ".$model->getId()));
        }
        return $this;
    }

    /**
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create()->addFieldToFilter("is_active", 1);
    }

    /**
     * @return \Codilar\Carousel\Api\Data\CarouselInterface[]
     */
    public function getCarousels()
    {
        $collection = $this->getCollection();
        $data = [];
        if ($collection->getSize()) {
            /** @var Carousel $item */
            foreach ($collection as $item) {
                /** @var CarouselInterface $carousel */
                $carousel = $this->carouselInterfaceFactory->create();
                $carouselItems = $this->getCarouselItems($item->getId());
                $carousel->setTitle($item->getTitle())
                         ->setSortOrder($item->getSortOrder())
                         ->setStoreViews($item->getStoreViews())
                         ->setIsActive($item->getIsActive())
                         ->setCreatedAt($item->getCreatedAt())
                         ->setDesignIdentifier($item->getDesignIdentifier())
                         ->setItems($carouselItems);
                $data[] = $carousel;
            }
        }
        return $this->sendResponse($data);
    }

    /**
     * @param int $carouselId
     * @return \Codilar\Carousel\Api\Data\CarouselItemsInterface[]|null
     */
    protected function getCarouselItems($carouselId)
    {
        $collection = $this->carouselItemRepository->getCollection()->addFieldToFilter("carousel_id", $carouselId);
        $items = [];
        if ($collection->getSize()) {
            /** @var Model\Item $item */
            foreach ($collection as $item) {
                /** @var CarouselItemsInterface $carouselItem */
                $carouselItem = $this->carouselItemInterfaceFactory->create();
                $content = $this->filter->filterStaticContent($item->getContent());
                $link = $this->getItemLink($item->getLink());
                $carouselItem->setId($item->getId())
                             ->setCarouselId($item->getCarouselId())
                             ->setLabel($item->getLabel())
                             ->setContent($content)
                             ->setLink($link)
                             ->setCreatedAt($item->getCreatedAt());
                $items[] = $carouselItem;
            }
            return $items;
        }
        return null;
    }

    /**
     * @param string $encodedLink
     * @return \Codilar\Carousel\Api\Data\CarouselItemLinkInterface|null
     */
    protected function getItemLink($encodedLink)
    {
        /** @var \Codilar\Carousel\Api\Data\CarouselItemLinkInterface  $link */
        $link = $this->carouselItemLinkInterfaceFactory->create();
        $encodedLink = json_decode($encodedLink, true);
        if (is_array($encodedLink)) {
            $link->setIdentifier($encodedLink['identifier'])
                 ->setType($encodedLink['type']);
            return $link;
        }
        return null;
    }
}