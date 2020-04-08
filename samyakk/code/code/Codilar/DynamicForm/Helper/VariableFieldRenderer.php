<?php
/**
 *
 * @package     magento2.3
 * @author      Codilar Technologies
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        http://www.codilar.com/
 */

namespace Codilar\DynamicForm\Helper;


use Codilar\Core\Model\TemplateParser\TemplateParser;

class VariableFieldRenderer
{
    /**
     * @var TemplateParser
     */
    private $templateParser;

    /**
     * VariableFieldRenderer constructor.
     * @param TemplateParser $templateParser
     */
    public function __construct(
        TemplateParser $templateParser
    )
    {
        $this->templateParser = $templateParser;
    }

    /**
     * @param string $message
     * @param array $data
     * @return mixed
     */
    public function render($message, $data = [])
    {
        return $this->templateParser->parse($message, $data);
    }
}