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
use Magento\Mtf\Config\FileResolver\ScopeConfig;
use Magento\Theme\Block\Html\Header\Logo;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class HomePage implements MetaDataTypeInterface
{

    private $scopeConfig;
    /**
     * @var CmsPage
     */
    private $cmsPage;

    /**
     * Product constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param CmsPage $cmsPage
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        CmsPage $cmsPage
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->cmsPage = $cmsPage;
    }

    /**
     * @param string $id
     * @return \Codilar\Meta\Api\Data\MetaDataInterface
     */
    public function getMetaTypeData($id)
    {
        $pageUrl = $this->getHomepage();
        return $this->cmsPage->getMetaTypeData($pageUrl);
    }

    /**
     * @return string
     */
    protected function getHomepage() {
        return $this->scopeConfig->getValue('web/default/cms_home_page', ScopeInterface::SCOPE_STORES);
    }
}