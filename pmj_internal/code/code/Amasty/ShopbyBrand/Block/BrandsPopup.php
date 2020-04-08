<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBrand
 */


namespace Amasty\ShopbyBrand\Block;

/**
 * Class BrandsPopup
 *
 * @package Amasty\ShopbyBrand\Block
 */
class BrandsPopup extends \Amasty\ShopbyBrand\Block\Widget\BrandList
{
    /**
     * @var string
     */
    protected $_template = 'brands_popup.phtml';

    /**
     * @var bool
     */
    protected $shouldWrap = true;

    /**
     * @return string
     */
    public function getOnlyContent()
    {
        $this->shouldWrap = false;
        return $this->toHtml();
    }

    /**
     * @return bool
     */
    public function isShowPopup()
    {
        return $this->brandHelper->getModuleConfig('general/brands_popup');
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->brandHelper->getBrandLabel();
    }

    /**
     * @return string
     */
    public function getAllbrandsUrl()
    {
        return $this->brandHelper->getAllBrandsUrl();
    }

    /**
     * @return \Magento\Eav\Api\Data\AttributeOptionInterface[]|null
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAllBrands()
    {
        return $this->brandHelper->getBrandOptions();
    }

    /**
     * @return bool
     */
    public function isAllBrandsPage()
    {
        $path = $this->getRequest()->getOriginalPathInfo();
        if ($path && $path !== '/') {
            $isAllBrandsPage = strpos(
                $this->brandHelper->getAllBrandsUrl(),
                $path
            ) !== false;
        } else {
            $isAllBrandsPage = false;
        }

        return $isAllBrandsPage;
    }

    /**
     * @return bool
     */
    public function isShouldWrap()
    {
        return $this->shouldWrap;
    }
}
