<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Product\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_SECTION = "product";
    const XML_GROUP = "product_specification";

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
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function getSpecificationJson() {
        return \json_decode($this->getValue('product_specification_json'), true);
    }

    /**
     * @param string $field
     * @param string $group
     * @param string $section
     * @param string $scope
     * @param bool $validateIsActive
     * @return string
     */
    public function getValue($field, $group = self::XML_GROUP, $section = self::XML_SECTION, $scope = ScopeInterface::SCOPE_STORE, $validateIsActive = true) {
        $path = $section . '/' . $group . '/' . $field;
        if (!array_key_exists($path.$scope, $this->data)) {
            $this->data[$path.$scope] = $this->scopeConfig->getValue($path, $scope);
        }
        return $this->data[$path.$scope];
    }
}