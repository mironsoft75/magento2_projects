<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Code\Generator;


use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ObjectManager;
use Codilar\Onboarding\Code\Generator\OnboardingSectionsGenerator as Instance;
use Magento\Framework\Code\Generator\Io;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Reflection\MethodsMap;

class OnboardingSectionsGeneratorFactory
{
    /**
     * @var ObjectManager
     */
    private $objectManager;
    /**
     * @var string
     */
    private $instance;
    /**
     * @var DirectoryList
     */
    private $directoryList;

    /**
     * OnboardingSectionsGeneratorFactory constructor.
     * @param DirectoryList $directoryList
     * @param MethodsMap $methodsMap
     * @param $serviceTypeName
     * @param $serviceMethodName
     */
    public function __construct(
        DirectoryList $directoryList,
        MethodsMap $methodsMap,
        $serviceTypeName,
        $serviceMethodName
    )
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->directoryList = $directoryList;
        $this->instance = $methodsMap->getMethodReturnType($serviceTypeName, $serviceMethodName);
    }

    /**
     * @param array $sections
     * @return Instance
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function create($sections)
    {
        return $this->objectManager->create(Instance::class, [
            'sourceClassName' => Instance::class,
            'resultClassName' => $this->getInstanceName(),
            'ioObject' => $this->getIoObject(),
            'sections' => $sections
        ]);
    }

    /**
     * @return Io
     * @throws \Magento\Framework\Exception\FileSystemException
     */
    public function getIoObject()
    {
        return new Io(new File(), $this->directoryList->getPath(DirectoryList::GENERATED_CODE));
    }

    /**
     * @return string
     */
    public function getInstanceName(): string
    {
        return $this->instance;
    }
}