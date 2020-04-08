<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Offers\Model;

use Codilar\Api\Api\AbstractApi;
use Codilar\Api\Helper\Cookie;
use Codilar\Catalog\Api\Data\ProductInterface;
use Codilar\Offers\Api\HomepageBlocksRepositoryInterface;
use Codilar\Offers\Api\Data\HomepageBlocksInterface;
use Codilar\Offers\Model\HomepageBlocksFactory;
use Codilar\Offers\Model\ResourceModel\HomepageBlocks\CollectionFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObjectFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Api\SearchResultsInterfaceFactory;
use Magento\Framework\Webapi\Rest\Response;

class HomepageBlocksRepository extends AbstractApi implements HomepageBlocksRepositoryInterface
{
    CONST BLOCK_ACTIVE = 1;
    CONST BLOCK_INACTIVE = 0;

    protected $objectFactory;
    protected $collectionFactory;
    protected $searchResultsFactory;
    /**
     * @var ProductInterface
     */
    private $productInterface;

    /**
     * HomepageBlocksRepository constructor.
     * @param Cookie $cookieHelper
     * @param RequestInterface $request
     * @param Response $response
     * @param Session $customerSession
     * @param DataObjectFactory $dataObjectFactory
     * @param \Codilar\Offers\Model\HomepageBlocksFactory $objectFactory
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsInterfaceFactory $searchResultsFactory
     * @param ProductInterface $productInterface
     */
    public function __construct(
        Cookie $cookieHelper,
        RequestInterface $request,
        Response $response,
        Session $customerSession,
        DataObjectFactory $dataObjectFactory,
        HomepageBlocksFactory $objectFactory,
        CollectionFactory $collectionFactory,
        SearchResultsInterfaceFactory $searchResultsFactory,
        ProductInterface $productInterface
    )
    {
        parent::__construct($cookieHelper, $request, $response, $customerSession, $dataObjectFactory);
        $this->objectFactory = $objectFactory;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->productInterface = $productInterface;
    }


    /**
     * @param HomepageBlocksInterface $object
     * @return HomepageBlocksInterface
     * @throws CouldNotSaveException
     */
    public function save(HomepageBlocksInterface $object)
    {
        try
        {
            $object->save();
        }
        catch(\Exception $e)
        {
            throw new CouldNotSaveException(__($e->getMessage()));
        }
        return $object;
    }

    /**
     * @param $id
     * @return mixed
     * @throws NoSuchEntityException
     */
    public function getById($id)
    {
        $object = $this->objectFactory->create();
        $object->load($id);
        if (!$object->getId()) {
            throw new NoSuchEntityException(__('Object with id "%1" does not exist.', $id));
        }
        return $object;
    }

    public function delete(HomepageBlocksInterface $object)
    {
        try {
            $object->delete();
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($id)
    {
        return $this->delete($this->getById($id));
    }

    /**
     * @param SearchCriteriaInterface $criteria
     * @return mixed
     */
    public function getList(SearchCriteriaInterface $criteria)
    {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $collection = $this->collectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            $fields = [];
            $conditions = [];
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ? $filter->getConditionType() : 'eq';
                $fields[] = $filter->getField();
                $conditions[] = [$condition => $filter->getValue()];
            }
            if ($fields) {
                $collection->addFieldToFilter($fields, $conditions);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $objects = [];
        foreach ($collection as $objectModel) {
            $objects[] = $objectModel;
        }
        $searchResults->setItems($objects);
        return $searchResults;
    }

    /**
     * @return ResourceModel\HomepageBlocks\Collection
     */
    public function getCollection()
    {
        return $this->collectionFactory->create()->addFieldToFilter("is_active", self::BLOCK_ACTIVE)->addFieldToFilter("has_products", self::BLOCK_ACTIVE)->setOrder("sort_order", SortOrder::SORT_ASC);
    }

    /**
     * @return \Codilar\Offers\Api\Data\HomepageBlocksInterface[]
     */
    public function getBlocks()
    {
        $blockData = [];
        $offerBlocks = $this->getCollection();
        if ($offerBlocks->getSize()) {
            /** @var \Codilar\Offers\Api\Data\HomepageBlocksInterface $offerBlock */
            foreach ($offerBlocks as $offerBlock) {
                $blockData[] = $offerBlock;
            }
        }
        return $this->sendResponse($blockData);
    }
}
