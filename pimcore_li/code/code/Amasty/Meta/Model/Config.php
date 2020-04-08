<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Meta
 */


namespace Amasty\Meta\Model;

class Config extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    protected $_configInheritance = true;

    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    public function _construct()
    {
        parent::_construct();
        $this->_init('Amasty\Meta\Model\ResourceModel\Config');
    }

    /**
     * @return object
     */
    public function getCollection()
    {
        $collection = $this->getResourceCollection()->addCategoryFilter();

        return $collection;
    }

    /**
     * @return mixed
     */
    public function getCustomCollection()
    {
        $collection = $this->getResourceCollection()->addCustomFilter();

        return $collection;
    }

    /**
     * @param $url
     * @param null $storeId
     *
     * @return mixed
     */
    public function getConfigByUrl($url, $storeId = null)
    {
        $collection = $this->getResourceCollection();
        $collection->addUrlFilter($url, $storeId);
        $collection->getSelect()
            ->order("store_id DESC")
            ->order("priority DESC");

        return $collection;
    }

    public function beforeSave()
    {
        if (!$this->getIsCustom()) {
            $this->setIsCustom($this->getCategoryId() === null);
        }

        if ($this->_storeManager->isSingleStoreMode()) {
            $storeId = $this->_storeManager->getStore()->getId();
            $this->setStoreId($storeId);
        }

        if ($this->ifStoreConfigExists($this)) {
            throw new \Exception(__('Template already exists in chosen store'));
        }

        return parent::beforeSave();
    }

    public function ifStoreConfigExists(\Amasty\Meta\Model\Config $item)
    {

        $collection = $this->getResourceCollection()
            ->addFieldToFilter('store_id', $item->getStoreId());

        if ($item->getCategoryId()) {
            $collection
                ->addFieldToFilter('category_id', $item->getCategoryId())
                ->addFieldToFilter('is_custom', 0);
        } else {
            $collection
                ->addFieldToFilter('custom_url', $item->getCustomUrl())
                ->addFieldToFilter('is_custom', 1);
        }

        if ($item->getId()) {
            $collection->addFieldToFilter($this->getIdFieldName(), ['neq' => $item->getId()]);
        }

        return $collection->getSize() > 0;
    }

    public function getRecursionConfigData($paths, $storeId)
    {
        if (empty($paths)) {
            $paths = [[ \Magento\Catalog\Model\Category::TREE_ROOT_ID]];
        }

        $distances = [];

        foreach ($paths as $pathIndex => $path) {
            foreach ($path as $categoryIndex => $category) {
                if (isset($distances[$category])) {
                    $distances[$category]['distance'] = min(
                        $categoryIndex,
                        $distances[$category]
                    );
                } else {
                    $distances[$category] = [
                        'distance' => $categoryIndex,
                        'path'     => $pathIndex
                    ];
                }
            }
        }

        $queryIds = array_keys($distances);

        $configs = $this->getResourceCollection()
            ->addFieldToFilter('store_id', ['in' => [(int)$storeId, 0]])
            ->addFieldToFilter('category_id', ['in' => $queryIds])
            ->addFieldToFilter('is_custom', 0);

        $foundIds = $configs->getColumnValues('category_id');

        if (empty($foundIds)) {
            return [];
        }

        $bestPath = null;
        $minDistance = $distances[$foundIds[0]]['distance'] + 1;

        foreach ($distances as $id => $category) {
            if (in_array($id, $foundIds)) {
                if ($category['distance'] < $minDistance) {
                    $minDistance = $category['distance'];
                    $bestPath = $paths[$category['path']];
                }
            }
        }

        $result = [];
        $orders = array_flip($bestPath);
        foreach ($configs as $config) {
            if ($config->getCategoryId() == \Magento\Catalog\Model\Category::TREE_ROOT_ID) {
                // Lowest priority for default category
                $config->setOrder(sizeof($bestPath));
                $result []= $config;
            } elseif (in_array($config->getCategoryId(), $bestPath)) {
                $config->setOrder($orders[$config->getCategoryId()]);
                $result []= $config;
            }
        }

        usort($result, [$this, '_compareConfigs']);

        if (!$this->_configInheritance) {
            return [$result[0]];
        }

        return $result;
    }

    protected function _compareConfigs($a, $b)
    {
        if ($a->getOrder() < $b->getOrder()) {
            return -1;
        } elseif ($a->getOrder() > $b->getOrder()) {
            return 1;
        }

        return ($a->getStoreId() > $b->getStoreId()) ? 1 : -1;
    }
}
