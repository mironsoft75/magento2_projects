<?php
/**
 * @package     magepwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\OrderHandler\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_SECTION = "order_handler";
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
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
    }

    /**
     * @return bool
     */
    public function getIsActive() {
        return (bool)$this->getValue("active", self::XML_GROUP, self::XML_SECTION, ScopeInterface::SCOPE_STORE, false);
    }

    /**
     * @return string
     */
    public function getThresholdTime() {
        return $this->getValue('threshold_time');
    }

    /**
     * @return string
     */
    public function getOrderCancelTemplate() {
        return $this->getValue('order_cancel_email');
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
            $this->data[$path.$scope] = $validateIsActive && !$this->getIsActive() ? false : $this->scopeConfig->getValue($path, $scope);
        }
        return $this->data[$path.$scope];
    }
}