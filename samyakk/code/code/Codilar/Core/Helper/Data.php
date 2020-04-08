<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ResourceConnectionFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Stdlib\DateTime\Timezone;

class Data extends AbstractHelper
{
    const DATEFORMAT = "Y-m-d H:i:s";
    /**
     * @var Timezone
     */
    private $timezone;
    /**
     * @var ResourceConnectionFactory
     */
    private $resourceConnectionFactory;

    /**
     * Data constructor.
     * @param Context $context
     * @param Timezone $timezone
     * @param ResourceConnectionFactory $resourceConnectionFactory
     */
    public function __construct(
        Context $context,
        Timezone $timezone,
        ResourceConnectionFactory $resourceConnectionFactory
    ) {
        parent::__construct($context);
        $this->timezone = $timezone;
        $this->resourceConnectionFactory = $resourceConnectionFactory;
    }

    /**
     * @param string $dateFormat
     * @return string
     */
    public function getCurrentDate($dateFormat = self::DATEFORMAT)
    {
        return $this->timezone->date()->format($dateFormat);
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getFormattedDate($date)
    {
        $dateDay = explode(" ", $date);
        $dateDay = $dateDay[0];
        $dateTime = (explode(" ", $date));
        $dateTime = $dateTime[1];
        $dateTime = explode("-", $dateTime);
        $fromTime = $dateTime[0];
        $toTime = $dateTime[1];
        $dateDay = date_format(date_create($dateDay), "D, d M Y");
        $fromTime = date_format(date_create($fromTime), "h:iA");
        $toTime = date_format(date_create($toTime), "h:iA");

        return $dateDay . " " . $fromTime . " - " . $toTime;
    }

    /**
     * @param $date
     * @return false|string
     */
    public function getFormattedNormalDate($date)
    {
        return date_format(date_create($date), " d M Y");
    }

    /**
     * @param array $arr
     * @return bool
     */
    public function isAssociativeArray($arr)
    {
        return is_array($arr) && count(array_filter(array_keys($arr), 'is_string')) > 0;
    }

    /**
     * @param string $urlKey
     * @return mixed
     * @throws \Zend_Db_Statement_Exception
     */
    public function getEntityIdByUrlRewrite($urlKey)
    {
        $query = $this->getNewResourceConnection()->select()
            ->from('url_rewrite')
            ->columns(['entity_id'])
            ->where("request_path = '$urlKey'");
        return $query->query()->fetch();
    }

    /**
     * @param string $entityType
     * @param string $urlKey
     * @return int
     * @throws NoSuchEntityException
     */
    public function getEntityIdByUrlKey($entityType, $urlKey, $parentId = null)
    {
        if ($entityType === 'cms-page') {
            $sql = $this->getNewResourceConnection()->select()
                ->from('cms_page')
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['page_id'])
                ->where("identifier = '$urlKey'");
            $result = $sql->query()->fetchAll();
            if (!count($result)) {
                throw new NoSuchEntityException(__("The cms page you requested does not exist"));
            }
            return $result[0]['page_id'];
        } else {
            $entityTypeIdQuery = $this->getNewResourceConnection()->select()
                ->from('eav_entity_type')
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['entity_type_id'])
                ->where(sprintf("entity_type_code = '%s'", $entityType));

            $eavAttributeQuery = $this->getNewResourceConnection()->select()
                ->from('eav_attribute')
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['attribute_id'])
                ->where("attribute_code = 'url_key' AND entity_type_id = ($entityTypeIdQuery)");

            $mainQuery = $this->getNewResourceConnection()->select()
                ->from(['main_table' => $entityType . '_entity_varchar'])
                ->reset(\Zend_Db_Select::COLUMNS)
                ->columns(['entity_id'])
                ->where("value = '$urlKey' AND attribute_id = ($eavAttributeQuery)");

            $this->includeParentCheckInSelect($parentId, $entityType, $mainQuery);

            $result = $mainQuery->query()->fetchAll();
            if (!count($result)) {
                throw new NoSuchEntityException(__("The $entityType you requested does not exist"));
            }
            return (int)$result[0]['entity_id'];
        }
    }

    /**
     * @param int $parentId
     * @param string $entityType
     * @param \Magento\Framework\DB\Select $query
     */
    protected function includeParentCheckInSelect($parentId, $entityType, $query)
    {
        if ($entityType === \Magento\Catalog\Model\Category::ENTITY) {
            $query->joinLeft(
                ['pcis' => "catalog_category_entity"],
                'main_table.entity_id = pcis.entity_id',
                []
            )->order('pcis.level ASC');
            if ($parentId) {
                $query->where("pcis.parent_id = $parentId");
            }
        } elseif ($entityType === \Magento\Catalog\Model\Product::ENTITY) {
            $query->joinLeft(
                ['pcis' => "catalog_category_product"],
                'main_table.entity_id = pcis.product_id',
                []
            )->order('pcis.position ASC');
            if ($parentId) {
                $query->where('pcis.category_id', $parentId);
            }
        }
    }

    /**
     * @return \Magento\Framework\DB\Adapter\AdapterInterface
     */
    protected function getNewResourceConnection()
    {
        return $this->resourceConnectionFactory->create()->getConnection();
    }
}
