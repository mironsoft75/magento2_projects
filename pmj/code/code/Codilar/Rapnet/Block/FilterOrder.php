<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 29/8/19
 * Time: 10:43 AM
 */

namespace Codilar\Rapnet\Block;

use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\Element\Template;
use Codilar\Rapnet\Api\RapnetRepositoryInterface;
use Psr\Log\LoggerInterface;
use Codilar\Rapnet\Helper\Data as RapnetHelper;

/**
 * Class FilterOrder
 * @package Codilar\Rapnet\Block
 */
class FilterOrder extends Template
{
    /**
     * @var RapnetRepositoryInterface
     */
    protected $rapnetRepository;
    /**
     * @var LoggerInterface
     */
    protected $_logger;
    /**
     * @var RapnetHelper
     */
    protected $rapnetHelper;

    /**
     * FilterOrder constructor.
     * @param Context $context
     * @param RapnetRepositoryInterface $rapnetRepository
     * @param RapnetHelper $rapnetHelper
     * @param LoggerInterface $logger
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        RapnetRepositoryInterface $rapnetRepository,
        RapnetHelper $rapnetHelper,
        LoggerInterface $logger,
        array $data = [])
    {
        $this->rapnetRepository = $rapnetRepository;
        $this->rapnetHelper = $rapnetHelper;
        $this->_logger = $logger;
        parent::__construct($context, $data);
    }

    /**
     * @param $filters
     * @param $orders
     * @return array
     */
    public function sortFilters($filters, $orders)
    {
        try {
            $orderedFilter = [];
            foreach ($orders as $order) {
                foreach ($filters as $filter) {
                    if ($filter["label"] == $order) {
                        array_push($orderedFilter, $filter);
                    }
                }
            }
            return $orderedFilter;
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $shapes
     * @return array
     */
    public function shapeFilter($shapes)
    {
        try {
            $shapeOrder = (explode(",", $this->rapnetHelper->getShapeFilter()));
            return $this->sortFilters($shapes, $shapeOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }

    /**
     * @param $cuts
     * @return array
     */
    public function cutFilter($cuts)
    {
        try {
            $cutOrder = (explode(",", $this->rapnetHelper->getCutFilter()));
            return $this->sortFilters($cuts, $cutOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $colors
     * @return array
     */
    public function colorFilter($colors)
    {
        try {
            $colorOrder = (explode(",", $this->rapnetHelper->getColorFilter()));
            return $this->sortFilters($colors, $colorOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $clarity
     * @return array
     */
    public function clarityFilter($clarity)
    {
        try {
            $clarityOrder = (explode(",", $this->rapnetHelper->getClarityFilter()));
            return $this->sortFilters($clarity, $clarityOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $certificates
     * @return array
     */
    public function certificateFilter($certificates)
    {
        try {
            $certificateOrder = (explode(",", $this->rapnetHelper->getCertificateFilter()));
            return $this->sortFilters($certificates, $certificateOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $polish
     * @return array
     */
    public function polishFilter($polish)
    {
        try {
            $polishOrder = (explode(",", $this->rapnetHelper->getPolishFilter()));
            return $this->sortFilters($polish, $polishOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $symmetry
     * @return array
     */
    public function symmetryFilter($symmetry)
    {
        try {
            $symmetryOrder = (explode(",", $this->rapnetHelper->getSymmetryFilter()));
            return $this->sortFilters($symmetry, $symmetryOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @param $fluorescnece
     * @return array
     */
    public function fluorescneceFilter($fluorescnece)
    {
        try {
            $fluorescneceOrder = (explode(",", $this->rapnetHelper->getFlourescenceFilter()));
            return $this->sortFilters($fluorescnece, $fluorescneceOrder);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function rapnetPoductPrice()
    {
        try {
            return round(min($this->rapnetRepository->getCollection()->addFieldToSelect('diamond_price')->getData())['diamond_price']);
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }

    }
}