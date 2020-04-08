<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\CheckoutBankTransfer\Model;

use Codilar\Checkout\Model\Source\Mode;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{

    const XML_GROUP = "bank_transfer";
    const XML_SECTION = "payment";

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var array
     */
    private $data;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     * @param array $data
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager,
        array $data = []
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->data = $data;
        $this->storeManager = $storeManager;
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
    public function getTitle() {
        return $this->getValue("title");
    }

    /**
     * @return string
     */
    public function getInstructions() {
        return $this->getValue("instructions");
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