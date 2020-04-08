<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Shipping\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem;
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
    private $cache;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Csv
     */
    private $csv;

    private $allowedZipcodes = null;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     * @param Filesystem $filesystem
     * @param Csv $csv
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Filesystem $filesystem,
        Csv $csv
    )
    {
        $this->scopeConfig = $scopeConfig;
        $this->cache = [];
        $this->filesystem = $filesystem;
        $this->csv = $csv;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return (bool)$this->getValue('carriers/samyakk/active');
    }

    /**
     * @return string[]
     */
    public function getChargeableCategories()
    {
        $categories = $this->getValue('carriers/samyakk/chargeable_categories');
        return $categories ? explode(',', $categories) : [];
    }

    /**
     * @return array
     */
    public function getCountryWiseSlabs()
    {
        $slabs = \json_decode($this->getValue('carriers/samyakk/country_wise_slabs'), true);
        return $slabs && array_key_exists('slabs', $slabs) ? $slabs['slabs'] : [];
    }

    /**
     * @return array
     */
    public function getAllowedZipcodes()
    {
        if (!$this->allowedZipcodes) {
            try {
                if (!$this->getIsActive()) {
                    throw new LocalizedException(__("Shipping method is inactive"));
                }
                $allowedZipcodesFileName = $this->getValue('carriers/samyakk/allowed_zipcodes');
                $reader = $this->filesystem->getDirectoryRead(DirectoryList::VAR_DIR);
                $allowedZipcodes = $this->csv->getData($reader->getAbsolutePath('samyakk_allowed_zipcodes/' . $allowedZipcodesFileName));
                if (count($allowedZipcodes)) {
                    $headers = $allowedZipcodes[0];
                    unset($allowedZipcodes[0]);
                }
                $response = [];
                foreach ($allowedZipcodes as $allowedZipcode) {
                    if (count($allowedZipcode) === count($headers)) {
                        $response[] = array_combine($headers, $allowedZipcode);
                    }
                }
                $this->allowedZipcodes = $response;
            } catch (\Exception $e) {
                $this->allowedZipcodes = [];
            }
        }
        return $this->allowedZipcodes;
    }

    /**
     * @param string $path
     * @return string
     */
    public function getValue($path)
    {
        if (!array_key_exists($path, $this->cache)) {
            $this->cache[$path] = $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
        }
        return $this->cache[$path];
    }
}