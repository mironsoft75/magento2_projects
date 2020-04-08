<?php
/**
 * @package     magento 2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Carousel\Model\Carousel;


use Codilar\Carousel\Api\CarouselItemRepositoryInterface;
use Codilar\Carousel\Api\Data\CarouselItemLinkInterface;
use Codilar\Carousel\Api\Data\CarouselItemLinkInterfaceFactory;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item\Collection;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item\CollectionFactory;
use Codilar\Carousel\Model\Carousel\Item as Model;
use Codilar\Carousel\Model\ResourceModel\Carousel\Item as ResourceModel;
use Codilar\Carousel\Model\Carousel\ItemFactory as ModelFactory;
use Magento\Framework\Exception\AlreadyExistsException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

class CarouselItemRepository implements CarouselItemRepositoryInterface
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
     * @var CarouselItemLinkInterfaceFactory
     */
    private $carouselItemLinkInterfaceFactory;

    /**
     * CarouselItemRepositoryInterface constructor.
     * @param ResourceModel $resourceModel
     * @param ModelFactory $modelFactory
     * @param CollectionFactory $collectionFactory
     * @param CarouselItemLinkInterfaceFactory $carouselItemLinkInterfaceFactory
     */
    public function __construct(
        ResourceModel $resourceModel,
        ModelFactory $modelFactory,
        CollectionFactory $collectionFactory,
        CarouselItemLinkInterfaceFactory $carouselItemLinkInterfaceFactory
    )
    {
        $this->resourceModel = $resourceModel;
        $this->modelFactory = $modelFactory;
        $this->collectionFactory = $collectionFactory;
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
        try {
            $linkData = \json_decode($model->getLink(),true);
        } catch (\Exception $e) {
            $linkData = null;
        }

        if (is_array($linkData)) {
            /** @var CarouselItemLinkInterface $link */
            $link = $this->carouselItemLinkInterfaceFactory->create();
            $link->setType($linkData['type'])
                 ->setIdentifier($linkData['identifier']);
            $model->setLink($link);
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
        return $this->collectionFactory->create();
    }
}