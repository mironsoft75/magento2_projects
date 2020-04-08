<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Model\Onboarding;

use Codilar\Onboarding\Code\Generator\OnboardingSectionsGeneratorFactory;
use Codilar\Onboarding\Model\Onboarding\Config\Reader;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Config\CacheInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\SerializerInterface;

class Config extends \Magento\Framework\Config\Data
{
    private $aggregatedData;
    /**
     * @var OnboardingSectionsGeneratorFactory
     */
    private $onboardingSectionsGeneratorFactory;

    /**
     * Config constructor.
     * @param Reader $reader
     * @param CacheInterface $cache
     * @param OnboardingSectionsGeneratorFactory $onboardingSectionsGeneratorFactory
     * @param string $cacheId
     * @param SerializerInterface|null $serializer
     * @throws LocalizedException
     */
    public function __construct(
        Reader $reader,
        CacheInterface $cache,
        OnboardingSectionsGeneratorFactory $onboardingSectionsGeneratorFactory,
        string $cacheId = 'onboarding_config',
        ?SerializerInterface $serializer = null
    ) {
        parent::__construct($reader, $cache, $cacheId, $serializer);
        $this->onboardingSectionsGeneratorFactory = $onboardingSectionsGeneratorFactory;
        try {
            $objectManager = ObjectManager::getInstance();
            $this->aggregatedData = $objectManager->get($onboardingSectionsGeneratorFactory->getInstanceName());
        } catch (\ReflectionException $reflectionException) {
            $this->aggregatedData = $this->generateAggregatedData();
            if (!$this->aggregatedData) {
                throw new LocalizedException(__("Error generating onboarding aggregated data"));
            }
            $onboardingSectionsGeneratorFactory->getIoObject()->includeFile($this->aggregatedData);
        }
    }

    /**
     * @return bool
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function generateAggregatedData()
    {
        $onboardingSectionsGenerator = $this->onboardingSectionsGeneratorFactory->create($this->getSections());
        return $onboardingSectionsGenerator->generate();
    }

    /**
     * @return array
     */
    public function getSections()
    {
        return $this->get('sections');
    }
}
