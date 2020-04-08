<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBrand
 */


namespace Amasty\ShopbyBrand\Plugin\Xsearch\Block\Search;

use Amasty\ShopbyBrand\Block\Widget\BrandList;

class Brand
{
    /**
     * @var BrandList
     */
    private $brandList;

    public function __construct(BrandList $brandList)
    {
        $this->brandList = $brandList;
    }

    /**
     * @param \Amasty\Xsearch\Block\Search\Brand $subject
     * @param array $result
     * @return array
     */
    public function afterGetBrands($subject, array $result)
    {
        return array_merge($result, $this->brandList->getItems());
    }
}
