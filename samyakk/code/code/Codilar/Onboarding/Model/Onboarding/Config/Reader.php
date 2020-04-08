<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Model\Onboarding\Config;


use Codilar\Onboarding\Code\Generator\OnboardingSectionsGeneratorFactory;
use Magento\Framework\Exception\FileSystemException;

class Reader extends \Magento\Framework\Config\Reader\Filesystem
{

    protected $_idAttributes = [
        '/config/sections/section' => 'name'
    ];
    /**
     * @var OnboardingSectionsGeneratorFactory
     */
    private $onboardingSectionsGeneratorFactory;

    /**
     * Reader constructor.
     * @param \Magento\Framework\Config\FileResolverInterface $fileResolver
     * @param Converter $converter
     * @param SchemaLocator $schemaLocator
     * @param \Magento\Framework\Config\ValidationStateInterface $validationState
     * @param OnboardingSectionsGeneratorFactory $onboardingSectionsGeneratorFactory
     * @param string $fileName
     * @param array $idAttributes
     * @param string $domDocumentClass
     * @param string $defaultScope
     */
    public function __construct(
        \Magento\Framework\Config\FileResolverInterface $fileResolver,
        Converter $converter,
        SchemaLocator $schemaLocator,
        \Magento\Framework\Config\ValidationStateInterface $validationState,
        OnboardingSectionsGeneratorFactory $onboardingSectionsGeneratorFactory,
        string $fileName = 'onboarding.xml',
        array $idAttributes = [],
        string $domDocumentClass = \Magento\Framework\Config\Dom::class,
        string $defaultScope = 'global'
    )
    {
        parent::__construct(
            $fileResolver,
            $converter,
            $schemaLocator,
            $validationState,
            $fileName,
            $idAttributes,
            $domDocumentClass,
            $defaultScope
        );
        $this->onboardingSectionsGeneratorFactory = $onboardingSectionsGeneratorFactory;
    }

    /**
     * @param null $scope
     * @return array
     */
    public function read($scope = null)
    {
//        $this->deleteGeneratedInstanceIfExists();
        return parent::read($scope);
    }

    /**
     * @return $this
     */
    private function deleteGeneratedInstanceIfExists()
    {
        try {
            $instanceName = $this->onboardingSectionsGeneratorFactory->getInstanceName();
            $ioObject = $this->onboardingSectionsGeneratorFactory->getIoObject();
            $fileName = $ioObject->generateResultFileName($instanceName);
            if ($ioObject->fileExists($fileName)) {
                unlink($fileName);
            }
        } catch (FileSystemException $fileSystemException) {}
        return $this;
    }
}