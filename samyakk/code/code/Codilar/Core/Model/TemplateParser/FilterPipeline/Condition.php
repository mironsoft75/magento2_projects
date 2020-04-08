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

class Condition implements FilterPipelineInterface
{

    const IF_REGEX = '/{{@(if|ifnot) \((.+?)(?: ((?:lt)|lteq|gt|gteq|eq|neq))?(?: (.+?))?\)}}(.*?){{@endif}}/s';

    /**
     * @var array
     */
    private $data = [];
    /**
     * @var Variable
     */
    private $variable;

    /**
     * Condition constructor.
     * @param Variable $variable
     */
    public function __construct(
        Variable $variable
    )
    {
        $this->variable = $variable;
    }

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
        /* Filter conditions */
        return preg_replace_callback(self::IF_REGEX, [$this, '_filterConditions'], $template);
    }

    /**
     * @param array $matches
     * @return string
     */
    private function _filterConditions($matches) {
        $lhs = $this->getVariable($matches[2]);
        $operator = $matches[3];
        $rhs = $this->getVariable($matches[4]);
        $value = $matches[5];

        try {
            $conditionSuccess = false;
            if (!$operator && $rhs) {
                throw new \InvalidArgumentException("Operator is required");
            }
            if ($operator) {
                switch ($operator) {
                    case 'lt':
                        $conditionSuccess = $lhs < $rhs;
                        break;
                    case 'lteq':
                        $conditionSuccess = $lhs <= $rhs;
                        break;
                    case 'gt':
                        $conditionSuccess = $lhs > $rhs;
                        break;
                    case 'gteq':
                        $conditionSuccess = $lhs >= $rhs;
                        break;
                    case 'eq':
                        $conditionSuccess = $lhs == $rhs;
                        break;
                    case 'neq':
                        $conditionSuccess = $lhs != $rhs;
                        break;
                }
            } else {
                $conditionSuccess = (bool)$lhs;
            }
            if ($matches[1] === 'if') {
                return $conditionSuccess ? $value : '';
            } else {
                return $conditionSuccess ? '' : $value;
            }
        } catch (\InvalidArgumentException $invalidArgumentException) {
            return $matches[0];
        }
    }

    private function getVariable($variable)
    {
        $value = $this->variable->getValue($this->data, $variable);
        if (!is_null($value)) {
            return $value;
        }
        if (is_numeric($variable)) {
            $variable = (double) $variable;
        }
        return $variable;
    }
}