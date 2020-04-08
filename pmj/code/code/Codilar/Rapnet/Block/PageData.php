<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 12/2/19
 * Time: 11:24 AM
 */

namespace Codilar\Rapnet\Block;

use Magento\Framework\View\Element\Template\Context;
use Codilar\Rapnet\Api\RapnetRepositoryInterface;
use Magento\Framework\Registry;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\App\CacheInterface;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;


/**
 * Class PageData
 * @package Codilar\Rapnet\Block
 */
class PageData extends \Magento\Framework\View\Element\Template
{

    /**
     * @var Registry
     */
    private $_registry;
    /**
     * @var RequestInterface
     */
    protected $_request;
    /**
     * @var CacheInterface
     */
    protected $_cache;
    /**
     * @var Json
     */
    protected $_serializer;
    /**
     * @var PriceHelper
     */
    private $priceHelper;
    /**
     * @var RapnetRepositoryInterface
     */
    private $rapnetRepository;


    /**
     * PageData constructor.
     * @param Context $context
     * @param Registry $registry
     * @param RequestInterface $request
     * @param PriceHelper $priceHelper
     * @param CacheInterface $cache
     * @param RapnetRepositoryInterface $rapnetRepository
     * @param Json|null $serializer
     */
    public function __construct(
        Context $context,
        Registry $registry,
        RequestInterface $request,
        PriceHelper $priceHelper,
        CacheInterface $cache,
        RapnetRepositoryInterface $rapnetRepository,
        Json $serializer = null
    )
    {
        $this->_registry = $registry;
        $this->_request = $request;
        $this->_cache = $cache;
        $this->_serializer = $serializer;
        $this->priceHelper = $priceHelper;
        $this->rapnetRepository = $rapnetRepository;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\View\Element\Template
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function _prepareLayout()
    {
        $blockName = 'codilar.rapnet.pager';
        $pager = null;
        if ($this->getPageCollection()) {
            if ($this->getLayout()->getBlock($blockName)) {
                $pager = $this->getLayout()->getBlock($blockName)
                    ->setAvailableLimit([10 => 10])
                    ->setShowPerPage(false)->setCollection(
                        $this->getPageCollection()
                    );
            } else {
                $pager = $this->getLayout()->createBlock(
                    'Magento\Theme\Block\Html\Pager',
                    $blockName
                )->setAvailableLimit([10 => 10])
                    ->setShowPerPage(false)->setCollection(
                        $this->getPageCollection()
                    );
            }
            $this->setChild('pager', $pager);
            $this->getPageCollection();
        }
        return $this;
    }

    /**
     * @return \Codilar\Rapnet\Model\ResourceModel\Rapnet\Collection
     */
    public function getPageCollection()
    {

        try {
            $sliderFilters = ['diamond_price', 'diamond_table_percent', 'diamond_depth_percent', 'diamond_carats'];
            $pageSize = 10;
            $params = $this->_request->getParams();
            $collection = $this->rapnetRepository->getCollection();
            $collection->setPageSize($pageSize);
            $collection->addFieldToSelect('*');
            if (isset($params['p'])) {
                $page = $params['p'];
                $collection->setCurPage($page);
                unset($params['p']);
            }
            if (count($params)) {
                foreach ($params as $attributCode => $param) {
                    if (in_array($attributCode, $sliderFilters)) {
                        $filters = explode("-", $param);
                        if (strlen($filters['1']) > 0 && strlen($filters['0']) > 0) {
                            $collection->addFieldToFilter($attributCode, [
                                ['from' => (float)$filters[0], 'to' => (float)$filters[1]]
                            ]);
                        }
                    } else {
                        $values = explode(",", $param);
                        $collection->addFieldToFilter($attributCode, $values);
                    }
                }
            }
            return $collection;
        } catch (\Exception $e) {
            $this->_logger->info($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * @param $price
     * @return float|string
     */
    public function getPrice($price)
    {
        return $this->priceHelper->currency($price, true, false);
    }

    /**
     * @param $request
     * @return array
     */
    public function getPriceValues($request)
    {
        if (array_key_exists('diamond_price', $request)) {
            $values = explode('-', $request['diamond_price']);
        } else {
            $values[0] = round(min($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_price')->getData())['diamond_price']);
            $values[1] = round(max($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_price')->getData())['diamond_price']);
        }
        return $values;
    }

    /**
     * @param $request
     * @return array
     */
    public function getCaratsValues($request)
    {
        if (array_key_exists('diamond_carats', $request)) {
            $values = explode('-', $request['diamond_carats']);
        } else {
            $values[0] = min($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_carats')->getData())['diamond_carats'];
            $values[1] = max($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_carats')->getData())['diamond_carats'];
        }
        return $values;
    }

    /**
     * @param $request
     * @return array
     */
    public function getTablePercent($request)
    {
        if (array_key_exists('diamond_table_percent', $request)) {
            $values = explode('-', $request['diamond_table_percent']);
        } else {
            $values[0] = min($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_table_percent')->getData())['diamond_table_percent'];
            $values[1] = max($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_table_percent')->getData())['diamond_table_percent'];
        }
        return $values;
    }

    /**
     * @param $request
     * @return array
     */
    public function getDepthPercent($request)
    {
        if (array_key_exists('diamond_depth_percent', $request)) {
            $values = explode('-', $request['diamond_depth_percent']);
        } else {
            $values[0] = min($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_depth_percent')->getData())['diamond_depth_percent'];
            $values[1] = max($this->rapnetRepository->getCollection()
                ->addFieldToSelect('diamond_depth_percent')->getData())['diamond_depth_percent'];
        }
        return $values;
    }

    /**
     * @param $data
     * @return string
     */
    public function getMeasurement($data)
    {
        if ($data['diamond_meas_width']) {
            if ($data['diamond_table_percent'] && $data['diamond_depth_percent']) {
                $measurement = $data['diamond_meas_width'] . '*' . $data['diamond_table_percent'] . '*' . $data['diamond_depth_percent'];
            } else {
                $measurement = '-';
            }
        } else {
            $measurement = '-';
        }
        return $measurement;
    }
}
