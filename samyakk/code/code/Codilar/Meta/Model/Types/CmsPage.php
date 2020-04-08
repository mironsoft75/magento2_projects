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
use Magento\Cms\Model\PageFactory;
use Magento\Theme\Block\Html\Header\Logo;

class CmsPage implements MetaDataTypeInterface
{
    /**
     * @var MetaManagementInterfaceFactory
     */
    private $metaManagementInterfaceFactory;
    /**
     * @var MetaDataInterfaceFactory
     */
    private $metaDataInterfaceFactory;
    /**
     * @var Logo
     */
    private $logo;
    /**
     * @var PageFactory
     */
    private $pageFactory;

    /**
     * Product constructor.
     * @param PageFactory $pageFactory
     * @param MetaManagementInterfaceFactory $metaManagementInterfaceFactory
     * @param MetaDataInterfaceFactory $metaDataInterfaceFactory
     * @param Logo $logo
     */
    public function __construct(
        PageFactory $pageFactory,
        MetaManagementInterfaceFactory $metaManagementInterfaceFactory,
        MetaDataInterfaceFactory $metaDataInterfaceFactory,
        Logo $logo
    )
    {
        $this->metaManagementInterfaceFactory = $metaManagementInterfaceFactory;
        $this->metaDataInterfaceFactory = $metaDataInterfaceFactory;
        $this->logo = $logo;
        $this->pageFactory = $pageFactory;
    }

    /**
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     */
    public function getMetaTypeData($id)
    {
        /** @var MetaDataInterface $data */
        $data = $this->metaDataInterfaceFactory->create();
        /** @var \Magento\Cms\Model\Page $page */
        $page = $this->pageFactory->create();
        $page->load($id);
        $title = ($page->getMetaTitle() == null) ? $page->getTitle() : $page->getMetaTitle();
        $data->setTitle($title);
        $data->setMeta($this->getMetaData($page));
        return $data;
    }

    /**
     * @param $page
     * @return \Codilar\Meta\Api\Data\MetaData\MetaInterface[]
     */
    public function getMetaData($page) {
        $metaDataArray = [];
        /** @var MetaManagementInterface $meta */
        $meta = $this->metaManagementInterfaceFactory->create();
        $metaDataArray[] = $meta->getMetaData("description", $page->getMetaDescription());
        $metaDataArray[] = $meta->getMetaData("keywords", $page->getMetaKeywords());

        $image = $this->logo->getLogoSrc();
        $metaDataArray[] = $meta->getMetaData("og:image", $image);
        $metaDataArray[] = $meta->getMetaData("og:image:secure_url", $image);

        $metaDataArray[] = $meta->getMetaData("og:type", 'cmspage');
        $title = ($page->getMetaTitle() == null) ? $page->getName() : $page->getMetaTitle();
        $metaDataArray[] = $meta->getMetaData("og:title", $title);
        return $metaDataArray;
    }
}