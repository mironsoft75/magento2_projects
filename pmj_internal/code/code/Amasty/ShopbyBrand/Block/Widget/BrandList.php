<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBrand
 */


namespace Amasty\ShopbyBrand\Block\Widget;

use Amasty\ShopbyBase\Api\Data\OptionSettingInterface;
use Amasty\ShopbyBrand\Model\Source\Tooltip;
use Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Framework\View\Element\Template\Context;
use Magento\Catalog\Model\ResourceModel\Layer\Filter\Attribute as FilterAttributeResource;
use \Magento\Eav\Model\Entity\Attribute\Option;
use Amasty\ShopbyBrand\Helper\Data as DataHelper;
use Amasty\ShopbyBase\Model\ResourceModel\OptionSetting\CollectionFactory as OptionSettingCollectionFactory;
use Magento\Search\Model\SearchEngine;

/**
 * Class BrandList
 *
 * @package Amasty\ShopbyBrand\Block\Widget
 */
class BrandList extends BrandListAbstract implements \Magento\Widget\Block\BlockInterface
{
    const CONFIG_VALUES_PATH = 'amshopby_brand/brands_landing';

    /**
     * @var FilterAttributeResource
     */
    protected $filterAttributeResource;

    /**
     * @var \Magento\CatalogInventory\Helper\Stock
     */
    protected $stockHelper;

    /**
     * @var \Magento\Catalog\Model\Product\Visibility
     */
    protected $catalogProductVisibility;

    /**
     * @var DataHelper
     */
    protected $brandHelper;

    /**
     * @var SearchEngine
     */
    protected $searchEngine;

    public function __construct(
        Context $context,
        Repository $repository,
        \Amasty\ShopbyBase\Model\OptionSettingFactory $optionSettingFactory,
        OptionSettingCollectionFactory $optionSettingCollectionFactory,
        \Magento\CatalogSearch\Model\Layer\Category\ItemCollectionProvider $collectionProvider,
        \Magento\Catalog\Model\Product\Url $productUrl,
        \Magento\Catalog\Api\CategoryRepositoryInterface $categoryRepository,
        FilterAttributeResource $filterAttributeResource,
        \Magento\CatalogInventory\Helper\Stock $stockHelper,
        \Magento\Catalog\Model\Product\Visibility $catalogProductVisibility,
        DataHelper $dataHelper,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Amasty\ShopbyBase\Api\UrlBuilderInterface $amUrlBuilder,
        \Amasty\ShopbyBrand\Helper\Data $brandHelper,
        SearchEngine $searchEngine,
        \Amasty\ShopbyBrand\Model\ProductCount $productCount,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $repository,
            $optionSettingFactory,
            $optionSettingCollectionFactory,
            $collectionProvider,
            $productUrl,
            $categoryRepository,
            $dataHelper,
            $messageManager,
            $amUrlBuilder,
            $productCount,
            $data
        );
        $this->filterAttributeResource = $filterAttributeResource;
        $this->stockHelper = $stockHelper;
        $this->catalogProductVisibility = $catalogProductVisibility;
        $this->brandHelper = $brandHelper;
        $this->searchEngine = $searchEngine;
    }

    /**
     * @return array
     */
    public function getIndex()
    {
        $items = $this->getItems();
        if (!$items) {
            return [];
        }

        $letters = $this->sortByLetters($items);
        $index = $this->breakByColumns($letters);

        return $index;
    }

    /**
     * @param array $items
     *
     * @return array
     */
    private function sortByLetters($items)
    {
        $this->sortItems($items);

        $letters = $this->items2letters($items);

        return $letters;
    }

    /**
     * @param array $letters
     *
     * @return array
     */
    private function breakByColumns($letters)
    {
        $columnCount = abs((int)$this->getData('columns'));
        if (!$columnCount) {
            $columnCount = 1;
        }

        $row = 0; // current row
        $num = 0; // current number of items in row
        $index = [];
        foreach ($letters as $letter => $items) {
            $index[$row][$letter] = $items['items'];
            $num++;
            if ($num >= $columnCount) {
                $num = 0;
                $row++;
            }
        }

        return $index;
    }

    /**
     * @param \Magento\Eav\Model\Entity\Attribute\Option $option
     * @param OptionSettingInterface $setting
     * @return array
     */
    protected function getItemData(Option $option, OptionSettingInterface $setting)
    {
        $count = $this->_getOptionProductCount($setting->getValue());
        if (!$this->helper->isDisplayZero() && !$count) {
            $result = [];
        } else {
            $result = [
                'label' => $setting->getLabel() ?: $option->getLabel(),
                'url' => $this->getBrandUrl($option),
                'img' => $setting->getSliderImageUrl(),
                'image' => $setting->getImageUrl(),
                'description' => $setting->getDescription(),
                'short_description' => $setting->getShortDescription(),
                'cnt' => $count,
                'alt' => $setting->getSmallImageAlt() ?: $setting->getLabel()
            ];
        }

        return $result;
    }

    /**
     * @param array $items
     */
    protected function sortItems(array &$items)
    {
        usort($items, [$this, '_sortByName']);
    }

    /**
     * @param array $items
     * @return array
     */
    protected function items2letters($items)
    {
        $letters = [];
        foreach ($items as $item) {
            if (function_exists('mb_strtoupper')) {
                $i = mb_strtoupper(mb_substr($item['label'], 0, 1, 'UTF-8'));
            } else {
                $i = strtoupper(substr($item['label'], 0, 1));
            }

            if (is_numeric($i)) {
                $i = '#';
            }
            if (!isset($letters[$i]['items'])) {
                $letters[$i]['items'] = [];
            }
            $letters[$i]['items'][] = $item;
            if (!isset($letters[$i]['count'])) {
                $letters[$i]['count'] = 0;
            }
            $letters[$i]['count']++;
        }

        return $letters;
    }

    /**
     * @return array
     */
    public function getAllLetters()
    {
        $brandLetters = [];
        /** @codingStandardsIgnoreStart */
        foreach ($this->getIndex() as $letters) {
            $brandLetters = array_merge($brandLetters, array_keys($letters));
        }
        /** @codingStandardsIgnoreEnd */

        return $brandLetters;
    }

    /**
     * @return string
     */
    public function getSearchHtml()
    {
        if (!$this->getData('show_search') || !$this->getItems()) {
            return '';
        }
        $searchCollection = [];
        foreach ($this->getItems() as $item) {
            $searchCollection[$item['url']] = $item['label'];
        }
        $searchCollection = json_encode($searchCollection);
        /** @var \Magento\Framework\View\Element\Template $block */
        $block = $this->getLayout()->createBlock(\Magento\Framework\View\Element\Template::class, 'ambrands.search')
            ->setTemplate('Amasty_ShopbyBrand::brand_search.phtml')
            ->setBrands($searchCollection);
        return $block->toHtml();
    }

    /**
     * @return bool
     */
    public function isTooltipEnabled()
    {
        $setting = $this->brandHelper->getModuleConfig('general/tooltip_enabled');

        return in_array(Tooltip::ALL_BRAND_PAGE, explode(',', $setting));
    }

    /**
     * @param array $item
     * @return string
     */
    public function getTooltipAttribute(array $item)
    {
        $result = '';
        if ($this->isTooltipEnabled()) {
            $result = $this->brandHelper->generateToolTipContent($item);
        }

        return $result;
    }

    /**
     * @return string
     */
    protected function getConfigValuesPath()
    {
        return self::CONFIG_VALUES_PATH;
    }

    /**
     * @return int
     */
    public function getImageWidth()
    {
        return abs($this->getData('image_width')) ?: 100;
    }

    /**
     * @return int
     */
    public function getImageHeight()
    {
        return abs($this->getData('image_height')) ?: 50;
    }
}
