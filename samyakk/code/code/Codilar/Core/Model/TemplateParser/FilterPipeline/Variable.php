<?php
/**
 *
 * @package     sampwamage
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\Core\Model\TemplateParser\FilterPipeline;


use Codilar\Core\Model\TemplateParser\FilterPipelineInterface;

class Variable implements FilterPipelineInterface
{

    const VARIABLE_REGEX = '/{{var (.*?)}}/';
    const DUMP_VAR = "__dump__";

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $template
     * @return string
     */
    public function filter($template)
    {
        /* Filter variables */
        return preg_replace_callback(self::VARIABLE_REGEX, [$this, '_filterVariables'], $template);
    }

    /**
     * @param array $data
     * @param string $path
     * @return mixed|null
     */
    public function getValue($data, $path)
    {
        $keys = explode(".", $path);
        foreach ($keys as $item) {
            if (array_key_exists($item, $data)) {
                $data = $data[$item];
            } else {
                $data = null;
                break;
            }
        }
        return $data;
    }

    /**
     * @param array $matches
     * @return string
     */
    private function _filterVariables($matches) {
        if ($matches[1] === self::DUMP_VAR) {
            $data = $this->data;
        } else {
            $data = $this->getValue($this->data, $matches[1]);
            if (is_null($data)) {
                $data = $matches[0];
            }
        }
        return is_array($data) ? \json_encode($data) : $data;
    }
}