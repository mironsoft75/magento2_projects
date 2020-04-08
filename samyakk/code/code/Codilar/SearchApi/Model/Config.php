<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\SearchApi\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_SECTION = "product";
    const XML_GROUP = "general";

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var array
     */
    private $data;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        array $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getSortOrder()
    {
        return $this->getValue('order');
    }

    /**
     * @return string
     */
    public function getSortOrderAttribute()
    {
        return $this->getValue('attribute');
    }

    /**
     * @return string
     */
    public function getLimit()
    {
        return $this->getValue('limit');
    }

    /**
     * @param string $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @param bool $validateIsActive
     * @return string
     */
    public function getValue($field, $group = self::XML_GROUP, $section = self::XML_SECTION, $scope = ScopeInterface::SCOPE_STORE, $validateIsActive = true)
    {
        $path = $section . '/' . $group . '/' . $field;
        if (!array_key_exists($path . $scope, $this->data)) {
            $this->data[$path . $scope] = $this->scopeConfig->getValue($path, $scope);
        }
        return $this->data[$path . $scope];
    }

    /**
     * @param $config_path
     * @return mixed
     */
    public function getConfig($config_path)
    {
        return $this->scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }
}
