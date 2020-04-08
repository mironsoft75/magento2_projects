<?php

/**
 * @package     htcPwa
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Api\Api;

use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Reflection\MethodsMap;
use Magento\Framework\Webapi\ServiceOutputProcessor;


class ResponseBuilder
{
    /**
     * @var ServiceOutputProcessor
     */
    private $serviceOutputProcessor;
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var MethodsMap
     */
    private $methodsMap;


    /**
     * Reflection constructor.
     * @param ServiceOutputProcessor $serviceOutputProcessor
     * @param ObjectManagerInterface $objectManager
     * @param MethodsMap $methodsMap
     */
    public function __construct(
        ServiceOutputProcessor $serviceOutputProcessor,
        ObjectManagerInterface $objectManager,
        MethodsMap $methodsMap
    )
    {
        $this->serviceOutputProcessor = $serviceOutputProcessor;
        $this->objectManager = $objectManager;
        $this->methodsMap = $methodsMap;
    }

    /**
     * @param $data
     * @param string $objectType
     * @param bool $graceful
     * @return array|\Magento\Framework\DataObject
     * @throws LocalizedException
     */
    public function arrayToObject($data, $objectType = DataObject::class, $graceful = false)
    {
        if ($this->isAssociativeArray($data)) {
            $result = $this->objectManager->create($objectType);
            $methods = $this->methodsMap->getMethodsMap($objectType);
            foreach ($data as $key => $datum) {
                $accessorMethodName = 'get' . str_replace('_', '', ucwords($key, '_'));
                if (array_key_exists($accessorMethodName, $methods)) {
                    $node = $this->arrayToObject($datum, $methods[$accessorMethodName]['type']);
                    $mutatorMethodName = 'set' . str_replace('_', '', ucwords($key, '_'));
                    if (!array_key_exists($mutatorMethodName, $methods)) {
                        if (!$graceful) {
                            throw new LocalizedException(__("Accessor method %method doesn't have a corresponding mutator in class %class", [
                                'method' => $accessorMethodName,
                                'class' => $objectType
                            ]));
                        } else {
                            $result->setData($key, $node);
                        }
                    } else {
                        $result->{$mutatorMethodName}($node);
                    }
                }
            }
        } else if (is_array($data)) {
            $result = [];
            foreach ($data as $datum) {
                $result[] = $this->arrayToObject($datum, substr($objectType, 0, -2));
            }
        } else {
            $result = $data;
        }
        return $result;
    }

    /**
     * @param object $object
     * @param string $objectType
     * @return array|object
     */
    public function objectToArray($object, $objectType)
    {
        return $this->serviceOutputProcessor->convertValue($object, $objectType);
    }

    /**
     * @param array $arr
     * @return bool
     */
    protected function isAssociativeArray($arr)
    {
        return is_array($arr) && count(array_filter(array_keys($arr), 'is_string')) > 0;
    }
}
