<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\BannerSlider\Block\Adminhtml\Banner\Edit\Category;

use Magento\Backend\Block\Template;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;
use Magestore\Bannerslider\Model\Banner;
use Codilar\BannerSlider\Helper\Category;

class FilterAttributes extends Template
{
    protected $_template = "Codilar_BannerSlider::banner/categories/attributes.phtml";
    protected $categoryFactory;
    protected $registry;
    protected $resourceBanner;
    protected $banner;
    protected $category;

    /**
     * FilterAttributes constructor.
     * @param Template\Context $context
     * @param CategoryFactory $categoryFactory
     * @param Registry $registry
     * @param Banner $banner
     * @param \Magestore\Bannerslider\Model\ResourceModel\Banner $resourceBanner
     * @param Category $category
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        Registry $registry,
        Banner $banner,
        \Magestore\Bannerslider\Model\ResourceModel\Banner $resourceBanner,
        Category $category,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory->create();
        $this->registry = $registry;
        $this->resourceBanner = $resourceBanner;
        $this->banner = $banner;
        $this->category = $category;
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getActiveCategories()
    {
        $categories = $this->categoryFactory->getCollection();
        $categories->addFieldToSelect(['name']);
        $categories->addFieldToFilter('entity_id', ['neq' => 2]);
        $categories->addIsActiveFilter();
        return $categories;
    }

    /**
     * @return \Magento\Catalog\Model\Category
     */
    public function getSelectedCategory()
    {
        return $this->getBannerData()->getCategory();
    }

    /**
     * @return mixed
     */
    public function getAttributeOptions()
    {
        $attrs = $this->getBannerData()->getData('attribute_options');
        if (!$attrs) {
            $attrs = '{}';
        }
        return json_decode($attrs, true);
    }

    /**
     * @return Banner
     */
    public function getBannerData()
    {
        $banner = $this->banner;
        $this->resourceBanner->load($banner, $this->getBannerId());
        $this->_logger->debug(json_encode($banner->getData()));
        return $banner;
    }

    /**
     * @return int
     */
    public function getBannerId()
    {
        return $this->getRequest()->getParam('banner_id');
    }

    /**
     * @param int $attrId
     * @return array
     */
    public function getAttributeOptionById($attrId)
    {
        return $this->category->getAttributeOptions($attrId);
    }

    /**
     * @param int $attrId
     * @return array|\Magento\Catalog\Api\Data\ProductAttributeInterface|\Magento\Eav\Api\Data\AttributeInterface
     */
    public function getAttributeData($attrId)
    {
        return $this->category->getAttributeDataById($attrId);
    }
}
