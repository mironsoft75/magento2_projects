<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_ShopbyBase
 */


namespace Amasty\ShopbyBase\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\DataObjectFactory as ObjectFactory;

/**
 * Class XmlSitemap
 */
class XmlSitemap
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * @var ObjectFactory
     */
    private $dataObjectFactory;

    /**
     * @var \Amasty\ShopbyBase\Helper\Data
     */
    private $baseHelper;

    /**
     * @var \Amasty\ShopbyBrand\Helper\Data
     */
    private $brandHelper;

    public function __construct(
        \Magento\Eav\Model\Config $eavConfig,
        \Amasty\ShopbyBase\Helper\Data $baseHelper,
        \Amasty\ShopbyBrand\Helper\Data $brandHelper,
        ObjectFactory $dataObjectFactory
    ) {
        $this->eavConfig = $eavConfig;
        $this->baseHelper = $baseHelper;
        $this->brandHelper = $brandHelper;
        $this->dataObjectFactory = $dataObjectFactory;
    }

    /**
     * @param string $attrCode
     * @return \Magento\Eav\Model\Entity\Attribute\AbstractAttribute
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getAttributeByCode($attrCode)
    {
        return $this->eavConfig->getAttribute(Product::ENTITY, $attrCode);
    }

    /**
     * @param $storeId
     * @param null $baseUrl
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getBrandUrls($storeId, $baseUrl = null)
    {
        $result = [];

        foreach ($this->getBrandOptions() as $option) {
            if ($option['value']) {
                $url = $this->brandHelper->getBrandUrl($option);
                if ($baseUrl) {
                    $url = str_replace($baseUrl, '', $url);
                }

                $result[] = $this->dataObjectFactory->create()->setUrl($url);
            }
        }

        return $result;
    }

    /**
     * @return array|\Magento\Eav\Api\Data\AttributeOptionInterface[]|null
     */
    public function getBrandOptions()
    {
        $options = [];
        $attrCode = $this->baseHelper->getBrandAttributeCode();
        if ($attrCode) {
            $brandAttribute = $this->getAttributeByCode($attrCode);
            $options = $brandAttribute->getOptions();
        }

        return $options;
    }
}
