<?php
/**
 *
 * @package     Magento 2.3
 * @author      Codilar
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://codilar.com/
 */

namespace Codilar\AdminLogs\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
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

    public function getIsActive()
    {
        return true;
    }

    /**
     * @return array
     */
    public function getWhitelistedActions() {
        $list = \json_decode($this->getValue("admin_logs/general/whitelist_action"), true);
        return $list ? array_column($list, 'full_action_name') : [];
    }

    /**
     * @return array
     */
    public function getBlacklistedActions() {
        $list = \json_decode($this->getValue("admin_logs/general/blacklist_action"), true);
        return $list ? array_column($list, 'full_action_name') : [];
    }

    /**
     * @return string[]
     */
    public function getWhitelistedModules() {
        $list = \json_decode($this->getValue("admin_logs/general/whitelist_module"), true);
        return $list ? array_column($list, 'module_name') : [];
    }

    /**
     * @return string[]
     */
    public function getBlacklistedModules() {
        $list = \json_decode($this->getValue("admin_logs/general/blacklist_module"), true);
        return $list ? array_column($list, 'module_name') : [];
    }

    /**
     * @return string[]
     */
    public function getAllowedRequests() {
        $requests = $this->getValue("admin_logs/general/allowed_requests");
        return $requests ? explode(",", $requests) : [];
    }

    /**
     * @param string $path
     * @param string $scope
     * @param bool $validateIsActive
     * @return string
     */
    public function getValue($path, $scope = ScopeInterface::SCOPE_STORE, $validateIsActive = true) {
        if (!array_key_exists($path.$scope, $this->data)) {
            $this->data[$path.$scope] = $validateIsActive && !$this->getIsActive() ? null : $this->scopeConfig->getValue($path, $scope);
        }
        return $this->data[$path.$scope];
    }
}