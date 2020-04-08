<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Customer\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return string
     */
    public function getServiceAccountJson()
    {
        return $this->getValue('service_account_json');
    }

    /**
     * @param string $field
     * @param string $group
     * @param string $section
     * @return string
     */
    protected function getValue($field, $group = "google", $section = "firebase")
    {
        return $this->scopeConfig->getValue($section."/".$group."/".$field, ScopeInterface::SCOPE_STORE);
    }
}