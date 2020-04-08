<?php
/**
 * Created by PhpStorm.
 * User: codilar
 * Date: 16/10/17
 * Time: 7:42 PM
 */

namespace Magestore\Bannerslider\Block\Adminhtml\Banner\Edit\Category;

use Magento\Backend\Block\Template;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Framework\Registry;
use Magestore\Bannerslider\Model\Banner;

class FilterAttributes extends Template
{
    protected $_template = "Codilar_Bannerslider::banner/categories/attributes.phtml";
    protected $categoryFactory;
    protected $registry;
    protected $resourceBanner;
    protected $banner;

    public function __construct(
        Template\Context $context,
        CategoryFactory $categoryFactory,
        Registry $registry,
        Banner $banner,
        \Magestore\Bannerslider\Model\ResourceModel\Banner $resourceBanner,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->categoryFactory = $categoryFactory->create();
        $this->registry = $registry;
        $this->resourceBanner = $resourceBanner;
        $this->banner = $banner;
    }

    public function getActiveCategories()
    {
        $categories = $this->categoryFactory->getCollection();
        $categories->addFieldToSelect(['name']);
        $categories->addFieldToFilter('entity_id', ['neq' => 2]);
        $categories->addIsActiveFilter();
        return $categories;
    }
}