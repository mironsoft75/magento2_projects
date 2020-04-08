<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Meta\Model\Types;


use Codilar\Meta\Api\Data\MetaData\MetaInterface;
use Codilar\Meta\Api\Data\MetaDataInterface;
use Codilar\Meta\Api\Data\MetaDataInterfaceFactory;
use Codilar\Meta\Api\MetaManagementInterface;
use Codilar\Meta\Api\MetaManagementInterfaceFactory;
use Codilar\Meta\Api\Types\MetaDataTypeInterface;
use Codilar\Meta\Model\MetaManagement;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Theme\Block\Html\Header\Logo;

class Category implements MetaDataTypeInterface
{
    /**
     * @var MetaDataInterfaceFactory
     */
    private $metaDataInterfaceFactory;
    /**
     * @var CategoryFactory
     */
    private $categoryFactory;
    /**
     * @var MetaManagementInterfaceFactory
     */
    private $metaManagementInterfaceFactory;
    /**
     * @var Logo
     */
    private $logo;

    /**
     * Category constructor.
     * @param MetaDataInterfaceFactory $metaDataInterfaceFactory
     * @param CategoryFactory $categoryFactory
     * @param MetaManagementInterfaceFactory $metaManagementInterfaceFactory
     * @param Logo $logo
     */
    public function __construct(
        MetaDataInterfaceFactory $metaDataInterfaceFactory,
        CategoryFactory $categoryFactory,
        MetaManagementInterfaceFactory $metaManagementInterfaceFactory,
        Logo $logo
    )
    {
        $this->metaDataInterfaceFactory = $metaDataInterfaceFactory;
        $this->categoryFactory = $categoryFactory;
        $this->metaManagementInterfaceFactory = $metaManagementInterfaceFactory;
        $this->logo = $logo;
    }

    /**
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     */
    public function getMetaTypeData($id)
    {
        /** @var MetaDataInterface $data */
        $data = $this->metaDataInterfaceFactory->create();
        /** @var \Magento\Catalog\Model\Category $category */
        $category = $this->categoryFactory->create()->load($id);
        $title = ($category->getMetaTitle() == null) ? $category->getName() : $category->getMetaTitle();
        $data->setTitle($title);
        $data->setMeta($this->getMetaData($category));
        return $data;
    }

    /**
     * @param \Magento\Catalog\Model\Category $category
     * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface[]
     */
    public function getMetaData($category) {
        $metaDataArray = [];
        /** @var MetaManagementInterface $meta */
        $meta = $this->metaManagementInterfaceFactory->create();
        $metaDataArray[] = $meta->getMetaData("description", $category->getMetaDescription());
        $metaDataArray[] = $meta->getMetaData("keywords", $category->getMetaKeywords());

        $image = $this->logo->getLogoSrc();
        $metaDataArray[] = $meta->getMetaData("og:image", $image);
        $metaDataArray[] = $meta->getMetaData("og:image:secure_url", $image);

        $metaDataArray[] = $meta->getMetaData("og:type", 'category');
        $title = ($category->getMetaTitle() == null) ? $category->getName() : $category->getMetaTitle();
        $metaDataArray[] = $meta->getMetaData("og:title", $title);
        return $metaDataArray;
    }
}