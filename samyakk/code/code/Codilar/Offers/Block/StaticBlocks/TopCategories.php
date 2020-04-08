<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 15/3/18
 * Time: 8:23 PM
 */

namespace Codilar\Offers\Block\StaticBlocks;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Asset\Repository as AssetRepository;

class TopCategories extends Template
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var AssetRepository
     */
    private $assetRepository;

    /**
     * TopCategories constructor.
     * @param Template\Context $context
     * @param CollectionFactory $collectionFactory
     * @param StoreManagerInterface $storeManager
     * @param AssetRepository $assetRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $collectionFactory,
        StoreManagerInterface $storeManager,
        AssetRepository $assetRepository,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->collectionFactory = $collectionFactory;
        $this->storeManager = $storeManager;
        $this->assetRepository = $assetRepository;
    }

    protected function _prepareLayout()
    {
        $this->setTemplate("Codilar_Offers::top_categories.phtml");
        return parent::_prepareLayout();
    }

    /**
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getCollection()
    {
        $collection = $this->collectionFactory->create();
        $collection->addAttributeToFilter('top_category',"1");
        $collection->addAttributeToFilter(Category::KEY_IS_ACTIVE, 1);
        $collection->addAttributeToSelect('*');
        $pageSize = 6; //$this->config->getNoOfCategoriesOnBlock();
        if ($pageSize > 0) {
            $collection->setPageSize($pageSize);
        }
        return $collection;
    }


    /**
     * @param Category $category
     * @return bool|string
     */
    public function getCategoryIcon(Category $category)
    {
        if ($category->getHomeIcon()) {
            try {
                return $category->getImageUrl("home_icon");
            } catch (LocalizedException $e) {
                return $this->assetRepository->getUrl('Codilar_Offers::images/default_category_icon.png');
            }
        } else {
            return $this->assetRepository->getUrl('Codilar_Offers::images/default_category_icon.png');
        }
    }
}