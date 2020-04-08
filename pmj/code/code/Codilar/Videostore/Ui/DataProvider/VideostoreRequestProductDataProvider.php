<?php
/**
 * Created by PhpStorm.
 * User: navaneeth
 * Date: 25/11/18
 * Time: 7:26 PM
 */

namespace Codilar\Videostore\Ui\DataProvider;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Codilar\Videostore\Api\VideostoreRequestRepositoryInterface;
use Magento\Framework\App\Request\Http;


class VideostoreRequestProductDataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * Product collection
     *
     * @var \Magento\Catalog\Model\ResourceModel\Product\Collection
     */
    protected $collection;

    /**
     * @var \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]
     */
    protected $addFieldStrategies;

    /**
     * @var \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]
     */
    protected $addFilterStrategies;


    protected $_videostoreRequestRepository;

    protected $request;
    /**
     * @var \Magento\Backend\Model\Session
     */
    private $backendSession;

    /**
     * VideostoreRequestProductDataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param VideostoreRequestRepositoryInterface $videostoreRequestRepository
     * @param Http $request
     * @param \Magento\Backend\Model\Session $backendSession
     * @param array $addFieldStrategies
     * @param array $addFilterStrategies
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        VideostoreRequestRepositoryInterface  $videostoreRequestRepository,
        Http $request,
        \Magento\Backend\Model\Session $backendSession,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        $this->_videostoreRequestRepository = $videostoreRequestRepository;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->addFieldStrategies = $addFieldStrategies;
        $this->addFilterStrategies = $addFilterStrategies;
        $this->request = $request;
        $this->backendSession = $backendSession;
    }

    public function getData()
    {
        $requestId = $this->request->getParam('videostore_request_id');
        if ($requestId) {
            $this->backendSession->setData("videostore_request_id", $requestId);
        } else {
            $requestId = $this->backendSession->getData("videostore_request_id");
        }
        $productIds = $this->_videostoreRequestRepository->getProductIds($requestId);

        /** @var string $productIds */
        $ids = explode(',', $productIds);

        $items = $this->getCollection()->addFieldToFilter('entity_id',$ids);

       $result = [
           'totalRecords' => $this->getCollection()->getSize(),
           'items' => array_values($items->toArray()),
       ];

        return  $result;
    }

    /**
     * Add field to select
     *
     * @param string|array $field
     * @param string|null $alias
     * @return void
     */
    public function addField($field, $alias = null)
    {
        if (isset($this->addFieldStrategies[$field])) {
            $this->addFieldStrategies[$field]->addField($this->getCollection(), $field, $alias);
        } else {
            parent::addField($field, $alias);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addFilter(\Magento\Framework\Api\Filter $filter)
    {
        if (isset($this->addFilterStrategies[$filter->getField()])) {
            $this->addFilterStrategies[$filter->getField()]
                ->addFilter(
                    $this->getCollection(),
                    $filter->getField(),
                    [$filter->getConditionType() => $filter->getValue()]
                );
        } else {
            parent::addFilter($filter);
        }
    }

}
