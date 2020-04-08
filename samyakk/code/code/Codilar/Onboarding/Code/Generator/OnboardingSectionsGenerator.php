<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Onboarding\Code\Generator;

use Magento\Framework\Code\Generator\DefinedClasses;
use \Magento\Framework\Code\Generator\EntityAbstract;
use \Magento\Framework\Code\Generator\CodeGeneratorInterface;
use Magento\Framework\Code\Generator\Io;
use Magento\Framework\Reflection\MethodsMap;

class OnboardingSectionsGenerator extends EntityAbstract
{

    const ENTITY_TYPE = "codilarOnboardingSections";
    /**
     * @var array
     */
    private $sections;
    /**
     * @var MethodsMap
     */
    private $methodsMap;

    /**
     * OnboardingSectionsGenerator constructor.
     * @param string $sourceClassName
     * @param string $resultClassName
     * @param Io $ioObject
     * @param array $sections
     * @param MethodsMap $methodsMap
     * @param CodeGeneratorInterface|null $classGenerator
     * @param DefinedClasses|null $definedClasses
     */
    public function __construct(
        string $sourceClassName,
        string $resultClassName,
        Io $ioObject,
        MethodsMap $methodsMap,
        array $sections,
        CodeGeneratorInterface $classGenerator = null,
        DefinedClasses $definedClasses = null
    )
    {
        parent::__construct($sourceClassName, $resultClassName, $ioObject, $classGenerator, $definedClasses);
        $this->sections = $sections;
        $this->methodsMap = $methodsMap;
    }

    /**
     * Get default constructor definition for generated class
     *
     * @return array
     */
    protected function _getDefaultConstructorDefinition()
    {
        return [];
    }

    /**
     * Returns list of methods for class generator
     *
     * @return array
     */
    protected function _getClassProperties()
    {
        $properties = [];
        foreach ($this->sections as $section) {

            $name = $section['name'];
            $camelCaseFormattedName = lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $name))));
            $type = $this->getReturnType($section['class'], $section['method']);

            $properties[] = [
                'name' => $camelCaseFormattedName,
                'visibility' => 'private',
                'docblock' => [
                    'shortDescription' => "Variable to store \${$camelCaseFormattedName}",
                    'tags' => [
                        [
                            'name' => 'var',
                            'description' => $type
                        ]
                    ],
                ],
            ];
        }
        return $properties;
    }

    protected function _validateData()
    {
        parent::_validateData();
        return true;
    }

    /**
     * Returns list of methods for class generator
     *
     * @return array
     */
    protected function _getClassMethods()
    {
        $methods = [];

        foreach ($this->sections as $section) {

            $name = $section['name'];
            $camelCaseFormattedName = lcfirst(str_replace(" ", "", ucwords(str_replace("_", " ", $name))));
            $type = $this->getReturnType($section['class'], $section['method']);

            $methods[] = [
                'name' => "get" . ucfirst($camelCaseFormattedName),
                'parameters' => [],
                'body' => "return \$this->{$camelCaseFormattedName};",
                'docblock' => [
                    'tags' => [
                        [
                            'name' => 'return',
                            'description' => $type
                        ]
                    ]
                ],
            ];

            $methods[] = [
                'name' => "set" . ucfirst($camelCaseFormattedName),
                'parameters' => [
                    [
                        'name' => $camelCaseFormattedName
                    ],
                ],
                'body' => "\$this->$camelCaseFormattedName = \${$camelCaseFormattedName};" . PHP_EOL . "return \$this;",
                'docblock' => [
                    'tags' => [
                        [
                            'name' => 'param',
                            'description' => "{$type} \${$camelCaseFormattedName}"
                        ],
                        [
                            'name' => 'return',
                            'description' => '$this'
                        ]
                    ]
                ],
            ];

        }

        return $methods;
    }

    protected function getReturnType($class, $method)
    {
        return $this->methodsMap->getMethodReturnType($class, $method);
    }
}