<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model\TemplateParser;

use Magento\Framework\DataObject;
use Magento\Framework\Webapi\ServiceOutputProcessor;


class Context
{
    /**
     * @var array
     */
    private $data;
    /**
     * @var ServiceOutputProcessor
     */
    private $outputProcessor;
    /**
     * @var array
     */
    private $filterPipeline;


    /**
     * VariableFieldRenderer constructor.
     * @param ServiceOutputProcessor $outputProcessor
     * @param array $filterPipeline
     * @param array $data
     */
    public function __construct(
        ServiceOutputProcessor $outputProcessor,
        array $filterPipeline = [],
        array $data = []
    )
    {
        $this->outputProcessor = $outputProcessor;
        $this->data = $this->formatData($data);
        $this->filterPipeline = $filterPipeline;
        uasort($this->filterPipeline, [$this, 'sortFilterPipeline']);
    }

    /**
     * @param array $data
     * @return array
     */
    public function getData($data = [])
    {
        if (count($data)) {
            $data = array_merge($this->data, $this->formatData($data));
        } else {
            $data = $this->data;
        }
        return $data;
    }

    /**
     * @return array
     */
    public function getFilterPipeline()
    {
        return $this->filterPipeline;
    }

    /**
     * @param array $first
     * @param array $second
     * @return bool
     */
    private function sortFilterPipeline($first, $second)
    {
        return (int)$first['sort_order'] > (int)$second['sort_order'];
    }

    /**
     * @param array $data
     * @return array
     */
    private function formatData($data)
    {
        $formattedData = [];
        if (!is_array($data)) {
            return $data;
        }
        foreach ($data as $key => $item) {
            if (is_object($item)) {
                if ($item instanceof DataObject) {
                    $formattedData[$key] = $this->formatData($item->getData());
                } else {
                    $formattedData[$key] = $this->outputProcessor->convertValue($item, get_class($item));
                }
            } else {
                $formattedData[$key] = $item;
            }
        }
        return $formattedData;
    }

}