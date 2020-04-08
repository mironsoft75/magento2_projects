<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\MegaMenu\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriterInterface;
use Magento\Framework\App\Cache\TypeListInterface as CacheType;

class Links
{
    /**
     * @var string
     */
    protected $configKey = "codilar/megamenu/links";

    /**
     * @var string
     */
    protected $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT;

    /**
     * @var null|array
     */
    protected $data = null;

    /**
     * @var ConfigWriterInterface
     */
    private $configWriter;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var CacheType
     */
    private $cacheType;

    /**
     * Links constructor.
     * @param ConfigWriterInterface $configWriter
     * @param ScopeConfigInterface $scopeConfig
     * @param CacheType $cacheType
     */
    public function __construct(
        ConfigWriterInterface $configWriter,
        ScopeConfigInterface $scopeConfig,
        CacheType $cacheType
    )
    {
        $this->configWriter = $configWriter;
        $this->scopeConfig = $scopeConfig;
        $this->cacheType = $cacheType;
    }

    /**
     * @return array
     */
    public function getUsedLinks()
    {
        return $this->get()['used'];
    }

    /**
     * @param array $links
     * @return $this
     */
    public function setUsedLinks($links)
    {
        $data = $this->get();
        $data['used'] = $links;
        return $this->set($data);
    }

    /**
     * @return array
     */
    public function getUnusedLinks()
    {
        return $this->get()['unused'];
    }

    /**
     * @param array $links
     * @return $this
     */
    public function setUnusedLinks($links)
    {
        $data = $this->get();
        $data['unused'] = $links;
        return $this->set($data);
    }

    /**
     * @return array
     */
    private function get()
    {
        if (!$this->data) {
            $data = \json_decode($this->scopeConfig->getValue($this->configKey, $this->scopeType, 0), true);
            if (!$data) {
                $data = [
                    'used' => [],
                    'unused' => []
                ];
            }
            $this->data = $data;
        }
        return $this->data;
    }

    /**
     * @param array $data
     * @return $this
     */
    private function set($data)
    {
        if (!$data['used']) {
            $data['used'] = [];
        }
        if (!$data['unused']) {
            $data['unused'] = [];
        }
        $this->data = $data;
        return $this;
    }

    public function commit()
    {
        $this->configWriter->save($this->configKey, \json_encode($this->get()), $this->scopeType, 0);
        $this->cacheType->cleanType('config');
    }

}