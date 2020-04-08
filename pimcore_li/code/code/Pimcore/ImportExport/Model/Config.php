<?php
/**
 * Created by salman.
 * Date: 5/9/18
 * Time: 6:11 PM
 * Filename: Config.php
 */

namespace Pimcore\ImportExport\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Class Config
 * @package Pimcore\ImportExport\Model
 */
class Config
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return mixed
     */
    public function getApiKey()
    {
        $apiKey = $this->scopeConfig->getValue(
            'pimcore/import_export/api_key',
            ScopeInterface::SCOPE_STORE
        );
        return $apiKey;
    }

    /**
     * @return mixed
     */
    public function getApiUrl()
    {
        $apiUrl = $this->scopeConfig->getValue(
            'pimcore/import_export/api_url',
            ScopeInterface::SCOPE_STORE
        );
        return $apiUrl;
    }

    /**
     * @return bool
     */
    public function isDebugModeEnabled()
    {
        $debugModeEnabled = $this->scopeConfig->isSetFlag(
            'pimcore/import_export/debug',
            ScopeInterface::SCOPE_STORE
        );
        return $debugModeEnabled;
    }
}
